const express = require('express');
const { validateSignature } = require('./Webhook');  // Importar la función de validación
const bodyParser = require('body-parser');

const app = express();
const secret = 'caaf8237923bf11cec5d3edcb90a5a208f152980d1745557a56b3d7741d0670d';  // Clave secreta del webhook

app.use(bodyParser.json()); // Parsear el cuerpo de la petición como JSON

app.post('/webhook', (req, res) => {
    const headers = req.headers;
    const body = req.body;  // Extraer el cuerpo de la petición

    console.log('Webhook received body:', body);

    const isValid = validateSignature(headers, body, secret);

    if (isValid) {
        console.log('Firma válida, procesando el webhook');
        res.status(200).send('OK');
    } else {
        console.log('Firma inválida, rechazando el webhook');
        res.status(400).send('Invalid signature');
    }
});

const port = 3000;
app.listen(port, () => {
    console.log(`Server listening on http://localhost:${port}`);
});
