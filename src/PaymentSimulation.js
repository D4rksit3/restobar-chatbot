// src/PaymentSimulation.js
import React, { useState } from 'react';
import './styles.css';

function PaymentSimulation({ onPaymentSuccess }) {
    const [cardNumber, setCardNumber] = useState('');
    const [expiry, setExpiry] = useState('');
    const [cvv, setCvv] = useState('');

    const handlePayment = () => {
        // Simulación simple de pago exitoso
        if (cardNumber && expiry && cvv) {
            onPaymentSuccess(); // Llamar a la función para manejar el éxito del pago
        } else {
            alert('Por favor, ingresa todos los detalles de la tarjeta.');
        }
    };

    return (
        <div className="modal">
            <div className="modal-content">
                <h2>Simulación de Pago</h2>
                <p>Inserta tu tarjeta y sigue las instrucciones para completar el pago.</p>
                <input
                    type="text"
                    placeholder="Número de tarjeta"
                    className="payment-input"
                    value={cardNumber}
                    onChange={(e) => setCardNumber(e.target.value)}
                />
                <input
                    type="text"
                    placeholder="Fecha de vencimiento (MM/AA)"
                    className="payment-input"
                    value={expiry}
                    onChange={(e) => setExpiry(e.target.value)}
                />
                <input
                    type="text"
                    placeholder="CVV"
                    className="payment-input"
                    value={cvv}
                    onChange={(e) => setCvv(e.target.value)}
                />
                <button onClick={handlePayment} className="finalizar-pago-button">Finalizar Pago</button>
            </div>
        </div>
    );
}

export default PaymentSimulation;
