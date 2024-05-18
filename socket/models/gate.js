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
        console.log(`Serial ${path} opened`);
      });

      this.port.on("data", async (bufferData) => {
        console.log(`${name} : ${bufferData.toString()}`);
        const data = bufferData.toString().slice(1, -1); // remove header and footer
        const prefix = data.slice(0, 2);
        if (!["W", "X"].includes(prefix)) return;
        let card_number = data.slice(2, 12); // take 36 characters only
        try {
          await fetch("http://localhost/api/accessLog", {
            method: "post",
            body: JSON.stringify({ card_number }),
            headers: {
              "Content-Type": "application/json",
              Accept: "application/json",
            },
          });
          // open gate
          this.port.write(Buffer.from(`\xA6TRIG1\xA9`));
        } catch (error) {
          console.log(error.message);
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
