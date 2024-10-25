// src/ModalCarta.js
import React, { useState } from 'react';
import './styles.css';

function ModalCarta({ categorias, productos, onAddToOrder, total, pedido, onCancelOrder, onConfirmOrder, onRemoveFromOrder }) {
    const [selectedCategoria, setSelectedCategoria] = useState(null);

    const filteredProductos = selectedCategoria
        ? productos.filter((producto) => producto.categoria_id === selectedCategoria.id)
        : [];

    return (
        <div className="modal">
            <div className="modal-content">
                <h2>Carta de Productos</h2>
                <div className="modal-sections">
                    <div className="categorias-section">
                        <h3>Categorías</h3>
                        <ul className="categorias-list">
                            {categorias.map((categoria) => (
                                <li key={categoria.id}>
                                    <button onClick={() => setSelectedCategoria(categoria)} className="categoria-button">
                                        {categoria.nombre}
                                    </button>
                                </li>
                            ))}
                        </ul>
                    </div>
                    <div className="productos-section">
                        {selectedCategoria && (
                            <>
                                <h3>Productos en {selectedCategoria.nombre}</h3>
                                <ul className="productos-list">
                                    {filteredProductos.map((producto) => (
                                        <li key={producto.id}>
                                            <button onClick={() => onAddToOrder(producto)} className="producto-button">
                                                {producto.nombre} - S/ {parseFloat(producto.precio).toFixed(2)}
                                            </button>
                                        </li>
                                    ))}
                                </ul>
                            </>
                        )}
                    </div>
                    <div className="resumen-section">
                        <h3>Resumen del Pedido</h3>
                        <ul className="resumen-list">
                            {pedido.map((p, index) => (
                                <li key={index}>
                                    {p.nombre} (x{p.cantidad}) - S/ {(p.precio * p.cantidad).toFixed(2)}
                                    <button onClick={() => onRemoveFromOrder(index)} className="remove-button">X</button>
                                </li>
                            ))}
                        </ul>
                        <p>Total: S/ {total.toFixed(2)}</p>

                        <div className="modal-footer">
                            <button onClick={onCancelOrder} className="cancel-button">Cancelar Pedido</button>
                            <button onClick={onConfirmOrder} className="realizar-pedido-button">Realizar Pedido</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}

export default ModalCarta;
