import React, { useState } from 'react';
import './PhoneLocationModal.css';

function PhoneLocationModal({ onSubmit, onClose }) {
    const [telefono, setTelefono] = useState('');
    const [ubicacion, setUbicacion] = useState('');
    const [error, setError] = useState('');

    // Obtener la ubicación en tiempo real
    const solicitarUbicacion = () => {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    const { latitude, longitude } = position.coords;
                    const mapsLink = `https://www.google.com/maps?q=${latitude},${longitude}`;
                    setUbicacion(mapsLink);
                },
                () => {
                    setError('No se pudo obtener la ubicación automáticamente.');
                }
            );
        } else {
            setError('La geolocalización no es compatible con tu navegador.');
        }
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        if (/^\d{9}$/.test(telefono) && ubicacion) {
            setError('');
            onSubmit({ telefono, ubicacion });
        } else {
            setError('Por favor, ingresa un número de teléfono válido de 9 dígitos y asegúrate de compartir tu ubicación.');
        }
    };

    return (
        <div className="modal-backdrop">
            <div className="modal">
                <h2>Bienvenido al Paladar de Cuchi</h2>
                <p>Por favor, registra tu número de teléfono y ubicación para continuar.</p>
                {error && <p className="error">{error}</p>}
                <form onSubmit={handleSubmit}>
                    <label htmlFor="telefono">Número de teléfono:</label>
                    <input
                        type="text"
                        id="telefono"
                        value={telefono}
                        onChange={(e) => setTelefono(e.target.value)}
                        placeholder="Ingresa tu número de teléfono"
                        required
                    />
                    <label htmlFor="ubicacion">Ubicación:</label>
                    <button type="button" onClick={solicitarUbicacion}>
                        Obtener ubicación en tiempo real
                    </button>
                    {ubicacion && (
                        <p>
                            Ubicación seleccionada: <a href={ubicacion} target="_blank" rel="noopener noreferrer">Ver en Google Maps</a>
                        </p>
                    )}
                    <button type="submit" className="submit-btn">Registrar</button>
                </form>
                <button onClick={onClose} className="close-btn">Cerrar</button>
            </div>
        </div>
    );
}

export default PhoneLocationModal;
