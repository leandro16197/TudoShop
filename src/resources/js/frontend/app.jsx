import React from "react"; // Ya no necesitas useState aquí si no lo usas
import { BrowserRouter, Routes, Route } from "react-router-dom";

import Navbar from "./components/Navbar";
import ProductDetail from "./pages/ProductDetail";
import Home from "./pages/homes";
import Catalogo from "./pages/Catalogo";
import Footer from "./components/Footer";
import Login from "./pages/Login";
import Register from "./pages/Registro";
import Perfil from "./pages/Perfil";
import { AuthProvider } from './context/AuthContext';
import { CartProvider } from './context/CartContext';

import "../../css/app.css";

export default function App() {
  return (
    <BrowserRouter>
      <CartProvider>
        <AuthProvider>
          <Navbar /> 
          
          <main className="app-content">
            <Routes>
              <Route path="/" element={<Home />} />
              <Route path="/home" element={<Home />} />
              
              <Route path="/catalogo" element={<Catalogo />} />
              <Route path="/productos/:id" element={<ProductDetail />} />
              <Route path="/login" element={<Login />} />
              <Route path="/registro" element={<Register />} />
              <Route path="/perfil" element={<Perfil />} />
            </Routes>
          </main>
          
          <Footer />
        </AuthProvider>
      </CartProvider>
    </BrowserRouter>
  );
}