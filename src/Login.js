// Login.js
import React, { useState } from 'react';
import Cookies from 'js-cookie';
import { getAuth, signInWithPopup, GoogleAuthProvider } from "firebase/auth";
import { FcGoogle } from 'react-icons/fc'; // Importamos el ícono de Google de react-icons
import './login.css';

function Login({ onLoginSuccess }) {
    const [loading, setLoading] = useState(false);
    const auth = getAuth();
    const provider = new GoogleAuthProvider();

    const handleLogin = async () => {
        setLoading(true);
        try {
            const result = await signInWithPopup(auth, provider);
            const user = result.user;
            const userData = { name: user.displayName, email: user.email };
            Cookies.set('user', JSON.stringify(userData), { expires: 7 });
            onLoginSuccess(user);
        } catch (error) {
            console.error("Error al iniciar sesión con Google:", error);
        } finally {
            setLoading(false);
        }
    };

    return (
        <div className="login-container">
            <div className="login-card">
                <h2>Bienvenido a Paladar de Cuchi</h2>
                <p>Inicia sesión para continuar</p>
                <button className="login-button" onClick={handleLogin} disabled={loading}>
                    {loading ? "Cargando..." : (
                        <>
                            <FcGoogle className="google-icon" /> {/* Icono de Google */}
                            Iniciar sesión con Google
                        </>
                    )}
                </button>
            </div>
        </div>
    );
}

export default Login;
