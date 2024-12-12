"use strict";
const { Model } = require("sequelize");
const { SerialPort } = require("serialport");
const { DelimiterParser } = require("@serialport/parser-delimiter");

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
            console.error(`${this.name} - ERROR - ${error.message}`);
          }
        }, 1000);
      } catch (error) {
        console.error(`${this.name} - ERROR - ${error.message}`);
      }
    }

    scan() {
      const { name, device: path, id: access_gate_id } = this;
      this.port = new SerialPort({
        path,
        baudRate: 9600,
      });

      console.log(`Connecting to gate ${name}...`);

      this.port.on("open", () => {
        console.log(`Serial ${path} (${name}) opened`);
      });

      const parser = this.port.pipe(new DelimiterParser({ delimiter: "#" }));
      parser.on("data", async (bufferData) => {
        const data = bufferData.toString();
        console.log(`${name} : ${data}`);
        const prefix = data[1];

        // skip kalau bukan detect card
        if (!"WX".includes(prefix)) {
          return;
        }

        let card_number = data.slice(2, 10);
        card_number = parseInt(card_number, 16); // convert to decimal
        console.log(`${name}: ${card_number}`);
        // hit api
        try {
          const res = await fetch("http://localhost/api/accessLog", {
            method: "POST",
            body: JSON.stringify({ card_number, prefix, access_gate_id }),
            headers: {
              "Content-Type": "application/json",
              Accept: "application/json",
            },
          });

          const json = await res.json();
          if (res.statusText != "OK") throw new Error(json.message);
          console.log(`${name}: ${JSON.stringify(json)}`);
          // open gate
          this.port.write(Buffer.from(`*TRIG1#`));
        } catch (error) {
          console.error(error.message);
        }
      });

      this.port.on("error", (error) => {
        console.error(`${name} - ERROR - ${error.message}`);
        this.reconnect();
      });
    }
  }

  Gate.init(
    {
      name: DataTypes.STRING,
      device: DataTypes.STRING,
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
