"use strict";
const { Model } = require("sequelize");
const { Socket } = require("net");

module.exports = (sequelize, DataTypes) => {
  class Gate extends Model {
    /**
     * Helper method for defining associations.
     * This method is not a part of Sequelize lifecycle.
     * The `models/index` file will call this method automatically.
     */
    static associate(models) {
      // define association here
    }

    socketClient;

    async reconnect() {
      try {
        await this.reload();

        if (this.socketClient instanceof Socket) {
          this.socketClient.removeAllListeners();
          this.socketClient.destroy();
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
      this.socketClient = new Socket();
      this.socketClient.setKeepAlive(true, 5000);
      const { name, host, port = 5000 } = this;
      console.log(`Connecting to gate ${name}...`);

      this.socketClient.connect(port, host, () => {
        console.log(`${name} - CONNECTED`);
      });

      this.socketClient.on("data", async (bufferData) => {
        const data = bufferData.toString().slice(1, -1); // remove header and footer
        const prefix = data.slice(0, 2);
        if (!["PT", "QT"].includes(prefix)) return;
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
          this.socketClient.write(Buffer.from(`\xA6TRIG1\xA9`));
        } catch (error) {
          console.log(error.message);
        }
      });

      this.socketClient.on("error", (error) => {
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
