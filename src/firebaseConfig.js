// src/firebaseConfig.js
import { initializeApp } from "firebase/app";
import { getAuth, GoogleAuthProvider, signInWithPopup, signOut } from "firebase/auth";

// Configura tu Firebase con las credenciales de tu proyecto
const firebaseConfig = {
    apiKey: "AIzaSyA2tnGGHxHSbQ7MoXtfZHwQDA1e3Pyu264",
    authDomain: "restobarproject-9d4dc.firebaseapp.com",
    projectId: "restobarproject-9d4dc",
    storageBucket: "restobarproject-9d4dc.appspot.com",
    messagingSenderId: "17405051659",
    appId: "1:17405051659:web:dbe43364ffb03719f7d80e"
};

// Inicializar Firebase
const app = initializeApp(firebaseConfig);
const auth = getAuth(app);

const provider = new GoogleAuthProvider();


export { auth, app, provider, signInWithPopup, signOut };

