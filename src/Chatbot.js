import React, { useState, useEffect, useCallback } from 'react';
import ModalCarta from './ModalCarta';
import Spinner from './Spinner';
import PhoneLocationModal from './PhoneLocationModal';
import PaymentSimulation from './PaymentSimulation';
import './styles.css';
import { getAuth } from "firebase/auth";
import { app } from './firebaseConfig';
import NavBar from './NavBar'; // Importamos NavBar

function Chatbot() {
    const auth = getAuth(app);
    const [user, setUser] = useState(null); // Para almacenar el usuario autenticado
    const [message, setMessage] = useState('');
    const [chatHistory, setChatHistory] = useState([]);
    const [categorias, setCategorias] = useState([]);
    const [productos, setProductos] = useState([]);
    const [pedido, setPedido] = useState([]);
    const [total, setTotal] = useState(0);
    const [modalVisible, setModalVisible] = useState(false);
    const [modalPhoneLocationVisible, setModalPhoneLocationVisible] = useState(false);
    const [ubicacion, setUbicacion] = useState(null);
    const [telefono, setTelefono] = useState('');
    const [codigoUsuario, setCodigoUsuario] = useState('');
    const [loading, setLoading] = useState(false);
    const [showPayment, setShowPayment] = useState(false);
    const [pedidoRealizado, setPedidoRealizado] = useState(null);
    const [awaitingPhoneNumber, setAwaitingPhoneNumber] = useState(!telefono);
    const [isTyping, setIsTyping] = useState(false);
    const [showLocationButton, setShowLocationButton] = useState(false);
    const [showCartaButton, setShowCartaButton] = useState(false);
    const [isFirstLogin, setIsFirstLogin] = useState(true);
    // Función para verificar si el usuario ya está registrado en la base de datos
    const verificarRegistroEnBD = async (uid) => {
        try {
            const response = await fetch(`https://restobar.losrealespicks.com/api/usuario_telefono?codigo_usuario=${uid}`);
            const data = await response.json();
    
            if (response.ok && data.telefono) {
                setTelefono(data.telefono);
                setUbicacion(data.ubicacion);
                setCodigoUsuario(uid);
                localStorage.setItem('codigo_usuario', uid);
                localStorage.setItem('telefono', data.telefono);
                setModalPhoneLocationVisible(false);
                setIsFirstLogin(false);
                /* saludoCompleto(); */  // Saludo completo con el botón de la carta
            } else {
                setModalPhoneLocationVisible(true);
            }
        } catch (error) {
            console.error('Error al verificar el registro en la base de datos:', error);
        }
    };
    

    // Verificar si el usuario ya ha registrado su número y ubicación
    useEffect(() => {
        const unsubscribe = auth.onAuthStateChanged((firebaseUser) => {
            if (firebaseUser) {
                setUser(firebaseUser);
                const storedCodigoUsuario = localStorage.getItem('codigo_usuario');
    
                if (!storedCodigoUsuario) {
                    verificarRegistroEnBD(firebaseUser.uid);
                } else {
                    setCodigoUsuario(storedCodigoUsuario);
                    const storedTelefono = localStorage.getItem('telefono');
                    setTelefono(storedTelefono);
                    /* saludoCompleto(); */ // Ejecutar saludo completo
                    if (isFirstLogin) { // Mostrar modal solo si es el primer inicio
                        setModalPhoneLocationVisible(true);
                    }
                }
            } else {
                console.error("No se ha autenticado ningún usuario.");
            }
        });
        return () => unsubscribe();
    }, [auth, isFirstLogin]);
    
/*     const saludoCompleto = () => {
        if (!user) {
            console.error("Usuario no disponible en saludoCompleto.");
            return;
        }
        simulateTyping(`Hola ${user.displayName || 'Usuario'}, bienvenido al Paladar de Cuchi.`, () => {
                simulateTyping(`Tu número registrado es: ${telefono}. Puedes buscar nuestros productos en la carta.`, () => {
                setShowCartaButton(true); // Mostrar botón para ver carta
            }); 
        });
    }; */
    
// Función para simular el efecto de escritura (sin escribir letra por letra)
const simulateTyping = (text, callback) => {
    // Mostrar que el bot está escribiendo
    setIsTyping(true);

    // Simular una pequeña pausa para el efecto de "escribiendo"
    setTimeout(() => {
        // Agregar el mensaje completo al historial
        setChatHistory((prev) => [
            ...prev, 
            { user: 'Bot', text }  // Se agrega el mensaje completo al historial
        ]);

        // Detener la simulación de "escribiendo"
        setIsTyping(false);

        // Ejecutar el callback si se proporciona
        if (callback) callback();
    }, 1000);  // Pausa de 2 segundos para el efecto de "escribiendo"
};

    
    
    
    
    

    // Función para registrar el teléfono y la ubicación
    const handleModalSubmit = async ({ telefono, ubicacion }) => {
        if (user) {
            try {
                const response = await fetch('https://restobar.losrealespicks.com/api/registro_usuario', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        codigo_usuario: user.uid,
                        nombre: user.displayName || "Nombre Desconocido",
                        telefono: telefono,
                        ubicacion: ubicacion,
                    }),
                });

                const data = await response.json();

                if (response.ok) {
                    setTelefono(telefono);
                    setUbicacion(ubicacion);
                    localStorage.setItem('codigo_usuario', data.codigo_usuario);
                    localStorage.setItem('telefono', telefono);
                    setModalPhoneLocationVisible(false); // Cerrar el modal después del registro
                    setIsFirstLogin(false); // Actualizar el estado para indicar que el registro se completó
               
                } else {
                    alert(`Error: ${data.error}`);
                }
            } catch (error) {
                console.error('Error al registrar usuario:', error);
            }
        } else {
            console.log("No hay un usuario autenticado.");
            alert("Error: No hay un usuario autenticado.");
        }
    };

    const mostrarPlatosMasConsumidos = async () => {
        try {
            const response = await fetch('https://restobar.losrealespicks.com/api/productos_mas_consumidos');
            const data = await response.json();
    
            // Verifica si hay productos y retorna la lista unida por comas
            if (data.productos && data.productos.length > 0) {
                return data.productos.join(', ');
            } else {
                return 'No se encontraron productos consumidos.';
            }
        } catch (error) {
            console.error('Error al obtener los productos más consumidos:', error);
            return 'Hubo un problema al obtener los productos más consumidos.';
        }
    };
    
    
    
    


   // Mostrar modal de modificar datos
   const handleModifyData = () => {
    setModalPhoneLocationVisible(true);
};


    const handleModalClose = () => {
        setModalPhoneLocationVisible(false);
    };

    const actualizarTelefono = async (nuevoTelefono) => {
        try {
            const response = await fetch('https://restobar.losrealespicks.com/api/actualizar_telefono', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    codigo_usuario: codigoUsuario,
                    telefono: nuevoTelefono,
                }),
            });

            if (response.ok) {
                setTelefono(nuevoTelefono);
                localStorage.setItem('telefono', nuevoTelefono);
                simulateTyping(`Número de teléfono actualizado a: ${nuevoTelefono}.`);
            } else {
                const data = await response.json();
                alert(`Error: ${data.error}`);
            }
        } catch (error) {
            console.error('Error al actualizar el número de teléfono:', error);
        }
    };

 // Función de saludo sin el efecto de escritura letra por letra
 const saludoInicial = useCallback(async () => {
    if (!user) {
        console.error("Usuario no autenticado o no disponible.");
        return;
    }

    const nombreUsuario = user?.displayName || 'Usuario';
    
    // Saludo sin efecto de escritura letra por letra, pero con pausa
    simulateTyping(`Hola ${nombreUsuario}, bienvenido al Paladar de Cuchi.`, async () => {
        const recomendaciones = await mostrarPlatosMasConsumidos();
        
        // Mostrar los productos más consumidos sin efecto de escribir letra por letra
        simulateTyping(`Nuestros platos más consumidos son: ${recomendaciones}.`, () => {
            setShowCartaButton(true);  // Mostrar el botón después del saludo
        });
    });
}, [user]);







    // Para verificar que el historial siempre se actualiza correctamente
    useEffect(() => {
        if (user) {
            saludoInicial();
            fetchCategorias();
            fetchProductos();
        }
    }, [user, saludoInicial]);

    const fetchCategorias = async () => {
        try {
            const response = await fetch('https://restobar.losrealespicks.com/api/categorias');
            if (!response.ok) throw new Error('Error al obtener las categorías');
            const data = await response.json();
            setCategorias(data);
        } catch (error) {
            console.error('Error al obtener las categorías:', error);
        }
    };

    const fetchProductos = async () => {
        try {
            const response = await fetch('https://restobar.losrealespicks.com/api/productos');
            if (!response.ok) throw new Error('Error al obtener los productos');
            const data = await response.json();
            setProductos(data);
        } catch (error) {
            console.error('Error al obtener los productos:', error);
        }
    };

    // Obtener recomendaciones de productos
/*     const obtenerRecomendaciones = async () => {
        try {
            const response = await fetch(`http://restobar.losrealespicks.com:5000/api/recomendaciones?telefono=${telefono}`);
            const data = await response.json();
            if (response.ok && data.recomendaciones.length > 0) {
                return data.recomendaciones.join(', ');
            } else {
                return 'No hay recomendaciones disponibles en este momento.';
            }
        } catch (error) {
            console.error('Error al obtener las recomendaciones:', error);
            return 'Error al obtener las recomendaciones.';
        }
    }; */

    const solicitarUbicacion = () => {
        if (navigator.geolocation) {
            setLoading(true);
            navigator.geolocation.getCurrentPosition(
                async (position) => {
                    setLoading(false);
                    const { latitude, longitude } = position.coords;
                    const mapsLink = `https://www.google.com/maps?q=${latitude},${longitude}`;
                    setUbicacion(mapsLink);

                    try {
                        const response = await fetch('https://restobar.losrealespicks.com/api/actualizar_ubicacion', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({
                                codigo_usuario: codigoUsuario,
                                ubicacion: mapsLink,
                            }),
                        });

                        if (response.ok) {
                            console.log('Ubicación actualizada en la base de datos.');
                        } else {
                            console.error('Error al actualizar la ubicación:', await response.text());
                        }
                    } catch (error) {
                        console.error('Error al enviar la ubicación al backend:', error);
                    }

                    simulateTyping('Ubicación recibida. ¿Deseas actualizar tu número de teléfono?');
                    setShowLocationButton(false);
                },
                () => {
                    setLoading(false);
                    simulateTyping('No se pudo obtener tu ubicación automáticamente. Selecciónala manualmente.');
                }
            );
        } else {
            simulateTyping('La geolocalización no es compatible con tu navegador.');
        }
    };

    const handleSendMessage = async () => {
        if (!message.trim()) return;

        setChatHistory((prev) => [...prev, { user: 'Cliente', text: message.trim() }]);

        if (awaitingPhoneNumber) {
            if (/^\d{9}$/.test(message.trim())) {
                setTelefono(message.trim());
                localStorage.setItem('telefono', message.trim());

                try {
                    const response = await fetch('https://restobar.losrealespicks.com/api/registro_usuario', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            codigo_usuario: codigoUsuario || user.uid,
                            nombre: user.displayName || "Usuario",
                            telefono: message.trim(),
                        }),
                    });

                    const data = await response.json();

                    if (response.ok) {
                        setCodigoUsuario(data.codigo_usuario);
                        localStorage.setItem('codigo_usuario', data.codigo_usuario);

                        simulateTyping(`Número de teléfono registrado: ${message.trim()}.`, () => {
                            simulateTyping('Ahora puedes ver nuestra carta.');
                            setShowCartaButton(true);
                        });
                        setAwaitingPhoneNumber(false);
                    } else {
                        alert(`Error: ${data.error}`);
                    }
                } catch (error) {
                    console.error('Error al registrar usuario:', error);
                }
            } else if (message.trim().toLowerCase() === 'no') {
                simulateTyping(`Continuando con el número registrado: ${telefono}.`);
                setAwaitingPhoneNumber(false);
            } else {
                alert('Por favor, ingresa un número de teléfono válido de 9 dígitos o escribe "no".');
            }
        } else {
            if (telefono && message.trim().toLowerCase() !== 'no') {
                actualizarTelefono(message.trim());
            } else {
                simulateTyping(`Continuando con el número registrado: ${telefono}.`);
            }
        }

        setMessage('');
    };

    const handleAddToOrder = (producto) => {
        const existingProduct = pedido.find((p) => p.id === producto.id);

        if (existingProduct) {
            const updatedPedido = pedido.map((p) =>
                p.id === producto.id ? { ...p, cantidad: p.cantidad + 1 } : p
            );
            setPedido(updatedPedido);
        } else {
            setPedido([...pedido, { ...producto, cantidad: 1 }]);
        }

        setTotal(total + parseFloat(producto.precio));
    };

    const handleRemoveFromOrder = (index) => {
        const producto = pedido[index];
        const nuevoPedido = pedido.filter((_, i) => i !== index);
        setPedido(nuevoPedido);
        setTotal(total - parseFloat(producto.precio));
    };

    const handleRealizarPedido = async () => {
        if (pedido.length === 0) {
            alert('Debes seleccionar al menos un producto para realizar el pedido.');
            return;
        }

        try {
            setLoading(true);
            const nroOrden = Math.floor(Math.random() * 100);

            const response = await fetch('https://restobar.losrealespicks.com/api/pedidos', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    productos: pedido.map((p) => ({
                        producto_id: p.id,
                        cantidad: p.cantidad,
                    })),
                    mozo_id: 2,
                    cliente: user.displayName || 'Cliente',
                    codigo_usuario: codigoUsuario,
                    mesa: 'Delivery',
                    nro_orden: nroOrden,
                    ubicacion: ubicacion,
                    telefono: telefono,
                }),
            });

            if (!response.ok) {
                throw new Error('Error al realizar el pedido.');
            }

            const pedidoData = await response.json();
            setPedidoRealizado({ ...pedidoData, productos: pedido });
            setLoading(false);
            setModalVisible(false);
            setShowPayment(true);
        } catch (error) {
            setLoading(false);
            console.error('Error al realizar el pedido:', error);
        }
    };

    const handlePaymentSuccess = () => {
        setShowPayment(false);
        setPedido([]);
        setTotal(0);
        simulateTyping('¡Pago realizado con éxito! Tu pedido está en camino.');
    };

    const handleCancelOrder = () => {
        setPedido([]);
        setTotal(0);
        setModalVisible(false);
        setShowPayment(false);
    };

    return (
        <div className="Chatbot-container">
            <NavBar onModifyData={handleModifyData} /> 
            {loading && <Spinner />}
            <div className="Chatbot">
                <div className="chat-history">
                    {chatHistory.map((msg, index) => (
                        <div key={index} className={msg.user === 'Cliente' ? 'user-message' : 'bot-message'}>
                            <span>{msg.user}: {msg.text}</span>
                        </div>
                    ))}
                    {showLocationButton && (
                        <button onClick={solicitarUbicacion} className="ubicacion-button">Compartir Ubicación</button>
                    )}
                    {showCartaButton && (
                        <button onClick={() => setModalVisible(true)} className="carta-button">Ver Carta</button>
                    )}
                    {isTyping && <div className="bot-typing">Bot está escribiendo...</div>}
                </div>
                <div className="chat-input">
                    <input
                        type="text"
                        value={message}
                        onChange={(e) => setMessage(e.target.value)}
                        placeholder={awaitingPhoneNumber ? "Ingresa tu número de teléfono o 'no' para continuar" : "Escribe tu mensaje..."}
                    />
                    <button className="send-button" onClick={handleSendMessage}>Enviar</button>
                </div>
            </div>

            {pedidoRealizado && (
                <div className="pedido-info">
                    <h3>Resumen del Pedido</h3>
                    <p>Estado: {pedidoRealizado.estado}</p>
                    <p>Ubicación: <a href={pedidoRealizado.ubicacion} target="_blank" rel="noopener noreferrer">Ver en Google Maps</a></p>
                    <h4>Platos:</h4>
                    <ul>
                        {pedidoRealizado.productos.map((p, index) => (
                            <li key={index}>
                                {p.nombre} (x{p.cantidad}) - S/ {(p.precio * p.cantidad).toFixed(2)}
                            </li>
                        ))}
                    </ul>
                </div>
            )}

            {modalVisible && (
                <ModalCarta
                    categorias={categorias}
                    productos={productos}
                    onAddToOrder={handleAddToOrder}
                    total={total}
                    pedido={pedido}
                    onCancelOrder={handleCancelOrder}
                    onConfirmOrder={handleRealizarPedido}
                    onRemoveFromOrder={handleRemoveFromOrder}
                />
            )}

            {showPayment && <PaymentSimulation onPaymentSuccess={handlePaymentSuccess} />}

            {modalPhoneLocationVisible && (
                <PhoneLocationModal
                    onSubmit={handleModalSubmit}
                    onClose={handleModalClose}
                />
            )}
        </div>
    );
}

export default Chatbot;
