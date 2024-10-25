// App.js
import React, { useEffect, useState } from 'react';
import Cookies from 'js-cookie';
import { getAuth, onAuthStateChanged } from "firebase/auth";
import Login from './Login';
import Chatbot from './Chatbot'; // O el componente principal de tu aplicación

function App() {
    const [user, setUser] = useState(null);
    const auth = getAuth();

    useEffect(() => {
        const unsubscribe = onAuthStateChanged(auth, (firebaseUser) => {
            if (firebaseUser) {
                setUser(firebaseUser); // Usuario autenticado
                const userData = { name: firebaseUser.displayName, email: firebaseUser.email };
                Cookies.set('user', JSON.stringify(userData), { expires: 7 });
            } else {
                setUser(null); // No hay usuario autenticado
                Cookies.remove('user');
            }
        });

        return () => unsubscribe();
    }, [auth]);

    if (!user) {
        return <Login onLoginSuccess={setUser} />;
    }

    return (
        <div>
            <Chatbot user={user} /> {/* Si el usuario ha iniciado sesión, mostramos el componente principal */}
        </div>
    );
}

export default App;
