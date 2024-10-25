import React, { useState, useEffect } from 'react';
import Cookies from 'js-cookie';
import { getAuth, signOut } from "firebase/auth";
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faUser } from '@fortawesome/free-solid-svg-icons';
import './NavBar.css'; // Importa el archivo de estilos aquí

const NavBar = ({ onModifyData }) => {
    const [menuVisible, setMenuVisible] = useState(false);
    const [userName, setUserName] = useState('Usuario'); // Estado para almacenar el nombre del usuario
    const auth = getAuth();

    // Cargar el nombre del usuario almacenado en las cookies
    useEffect(() => {
        const userCookie = Cookies.get('user');
        if (userCookie) {
            const userData = JSON.parse(userCookie);
            setUserName(userData.name);
        }
    }, []);

    const handleToggleMenu = () => {
        setMenuVisible(!menuVisible);
    };

    const handleSignOut = async () => {
        try {
            await signOut(auth); // Cerrar sesión de Firebase
            Cookies.remove('user'); // Eliminar la cookie del usuario
            window.location.reload(); // Recargar la página para actualizar el estado de la aplicación
        } catch (error) {
            console.error("Error al cerrar sesión:", error);
        }
    };

    return (
        <div className="navbar">
            {/* Título del sitio */}
            <div className="navbar-title">
                Paladar de Cuchi
            </div>

            {/* Icono de usuario */}
            <div className="user-icon" onClick={handleToggleMenu}>
                <FontAwesomeIcon icon={faUser} />
            </div>

            {/* Menú desplegable al hacer clic en el icono */}
            {menuVisible && (
                <div className="dropdown-menu">
                    <div className="user-name">
                        {userName} {/* Mostrar el nombre del usuario */}
                    </div>
                    
                    <button className="dropdown-item" onClick={onModifyData}>Modificar Datos</button>
                    <button className="dropdown-item" onClick={handleSignOut}>Cerrar Sesión</button>
                </div>
            )}
        </div>
    );
};

export default NavBar;
