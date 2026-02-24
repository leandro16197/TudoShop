import React, { createContext, useContext, useState, useEffect } from "react";
import { useNavigate } from "react-router-dom";

const CartContext = createContext();

export function CartProvider({ children }) {
    const navigate = useNavigate();
    const [cart, setCart] = useState([]);

    const fetchCart = async () => {
        const token = sessionStorage.getItem("token");
        if (!token) {
            setCart([]); 
            return;
        }

        try {
            const response = await fetch("/api/frontend/v1/pedidos/mi-carrito", {
                headers: { 
                    "Authorization": `Bearer ${token}`,
                    "Accept": "application/json"
                },
            });
            const data = await response.json();
            
            if (response.ok) {
                setCart(data.productos || []);
            }
        } catch (error) {
            console.error("Error al obtener el carrito:", error);
        }
    };

    const cartCount = cart.reduce((total, item) => total + (item.cantidad || 0), 0);
    const cartTotal = cart.reduce((total, item) => total + (item.precio * item.cantidad), 0).toFixed(2);

    useEffect(() => {
        fetchCart();
        window.addEventListener("authChange", fetchCart);
        return () => window.removeEventListener("authChange", fetchCart);
    }, []);

    const addToCart = async (product, quantity = 1) => {
      try {
          const token = sessionStorage.getItem("token");
          console.log("TOKEN:", token);
          if (!token) { return; } 

          const response = await fetch("/api/frontend/v1/pedidos/agregar-producto", {
              method: "POST",
              headers: {
                  "Content-Type": "application/json",
                  "Accept": "application/json", 
                  "Authorization": `Bearer ${token}`
              },
              body: JSON.stringify({
                  producto_id: product.id, 
                  cantidad: quantity,
              }),
          });

          const data = await response.json();

          if (response.status === 401) {
              sessionStorage.removeItem("token");
              navigate("/login");
              return;
          }

          if (!response.ok) throw new Error(data.message || "Error al agregar");

          await fetchCart(); 
          return data;
      } catch (error) {
          console.error("Error:", error.message);
          throw error;
      }
  };

    const removeFromCart = async (productoId) => {
        const token = sessionStorage.getItem("token");
        if (!token) return;

        try {
            const response = await fetch(`/api/frontend/v1/eliminar-producto/${productoId}`, {
                method: "DELETE",
                headers: { "Authorization": `Bearer ${token}` }
            });
            if (response.ok) await fetchCart();
        } catch (error) {
            console.error("Error al eliminar:", error);
        }
    };

    const updateQuantity = async (productoId, nuevaCantidad) => {
      console.log("ID enviado:", productoId, "Nueva Cantidad:", nuevaCantidad);
      if (nuevaCantidad < 1) return;
      setCart(prevCart => 
          prevCart.map(item => 
              item.id === productoId ? { ...item, cantidad: nuevaCantidad } : item
          )
      );

      try {
          const token = sessionStorage.getItem("token");
          const response = await fetch("/api/frontend/v1/actualizar-cantidad", {
              method: "POST", 
              headers: {
                  "Content-Type": "application/json",
                  "Authorization": `Bearer ${token}`,
                  "Accept": "application/json"
              },
              body: JSON.stringify({ 
                  producto_id: productoId, 
                  cantidad: nuevaCantidad 
              })
          });

          if (!response.ok) {
             
              await fetchCart();
          }
      } catch (error) {
          console.error("Error al actualizar:", error);
          await fetchCart(); 
      }
  };

    return (

        <CartContext.Provider value={{ cart, cartCount, cartTotal, addToCart, removeFromCart, updateQuantity, fetchCart }}>
            {children}
        </CartContext.Provider>
    );
}

export const useCart = () => {
    const context = useContext(CartContext);
    if (!context) throw new Error("useCart debe usarse dentro de un CartProvider");
    return context;
};