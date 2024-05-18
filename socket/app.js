const { Gate } = require("./models");

(async () => {
  const gates = await Gate.findAll();
  gates.forEach((gate) => {
    try {
      gate.scan();
    } catch (error) {
      console.log(error.message);
    }
  });
})();
