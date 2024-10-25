// src/Spinner.js
import React from 'react';
import './styles.css';

function Spinner() {
    return (
        <div className="spinner-overlay">
            <div className="spinner-circle"></div>
            <p>Procesando...</p>
        </div>
    );
}

export default Spinner;
