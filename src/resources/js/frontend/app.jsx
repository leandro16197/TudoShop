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
import PaginaCompra from "./pages/paginaCompra";
import { AuthProvider } from './context/AuthContext';
import { CartProvider } from './context/CartContext';
import axios from 'axios';
axios.defaults.baseURL = 'http://localhost:8000';
axios.interceptors.request.use((config) => {
    const token = localStorage.getItem('AUTH_TOKEN'); 
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    config.headers.Accept = 'application/json';
    return config;
});
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
              <Route path="/checkout" element={<PaginaCompra />} />
            </Routes>
          </main>
          
          <Footer />
        </AuthProvider>
      </CartProvider>
    </BrowserRouter>
  );
}