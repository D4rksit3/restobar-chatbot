const crypto = require('crypto');

// Función para validar la firma HMAC del webhook de Mercado Pago
function validateSignature(headers, body, secret) {
    // Obtener el valor de la firma desde los headers
    const xSignature = headers['x-signature']; 
    const xRequestId = headers['x-request-id']; 

    if (!xSignature) {
        console.error('Firma no proporcionada');
        return false;
    }

    // Separar la firma en partes
    const parts = xSignature.split(',');
    let ts, hash;

    // Extraer 'ts' y 'v1' de la firma
    parts.forEach(part => {
        const [key, value] = part.split('=');
        if (key && value) {
            if (key.trim() === 'ts') {
                ts = value.trim();
            } else if (key.trim() === 'v1') {
                hash = value.trim();
            }
        }
    });

    if (!ts || !hash) {
        console.error('No se encontraron las partes de ts o v1 en la firma');
        return false;
    }

    // Crear el string de datos que vamos a firmar
    const manifest = `id:${body.data.id};request-id:${xRequestId};ts:${ts};`;

    // Generar la firma HMAC utilizando el secreto y el string de datos
    const hmac = crypto.createHmac('sha256', secret);
    hmac.update(manifest);
    const generatedHash = hmac.digest('hex');

    // Comparar la firma generada con la firma recibida
    console.log(`Generated signature: ${generatedHash}`);
    console.log(`Received signature: ${hash}`);

    return generatedHash === hash;
}

module.exports = { validateSignature };
