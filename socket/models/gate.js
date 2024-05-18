"use strict";
const { Model } = require("sequelize");
const { SerialPort } = require("serialport");
const Readline = require("@serialport/parser-readline");

module.exports = (sequelize, DataTypes) => {
  class Gate extends Model {
    port;

    async reconnect() {
      try {
        await this.reload();

        if (this.port instanceof SerialPort) {
          this.port = null;
        }

        setTimeout(() => {
          try {
            this.scan();
          } catch (error) {
            console.log(`${this.name} - ERROR - ${error.message}`);
          }
        }, 1000);
      } catch (error) {
        console.log(`${this.name} - ERROR - ${error.message}`);
      }
    }

    scan() {
      const { name, host: path } = this;
      this.port = new SerialPort({
        path,
        baudRate: 9600,
      });

      console.log(`Connecting to gate ${name}...`);

      this.port.on("open", () => {
        console.log(`Serial ${path} (${name}) opened`);
      });

      let data = "";

      this.port.on("data", async (bufferData) => {
        data += bufferData.toString();

        // sudah akhir dari response
        if (data.includes("*W") && data.includes("#")) {
          let card_number = data.split("#")[0].slice(-8); // take 8 character only
          card_number = parseInt(card_number, 16); // convert to decimal
          console.log(`${name}: ${card_number}`);

          // hit api
          try {
            await fetch("http://localhost/api/accessLog", {
              method: "post",
              body: JSON.stringify({ card_number, ip: path }),
              headers: {
                "Content-Type": "application/json",
              },
            });
            // open gate
            this.port.write(Buffer.from(`*TRIG1#`));
          } catch (error) {
            console.log(error.message);
          }

          // reset data
          data = "";
        }
      });

      this.port.on("error", (error) => {
        console.log(`${name} - ERROR - ${error.message}`);
        this.reconnect();
      });
    }
  }

  Gate.init(
    {
      name: DataTypes.STRING,
      type: DataTypes.STRING,
      cameras: DataTypes.JSON,
      host: DataTypes.STRING,
    },
    {
      sequelize,
      modelName: "Gate",
      tableName: "access_gates",
      timestamps: false,
    }
  );

  return Gate;
};
