import React, { useState } from "react";
import { useNavigate } from "react-router-dom";
import { Link } from "react-router-dom";

export default function Login() {
  const navigate = useNavigate();

  const [form, setForm] = useState({
    email: "",
    password: ""
  });

  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);

  const handleChange = (e) => {
    setForm({
      ...form,
      [e.target.name]: e.target.value
    });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();

    if (!form.email || !form.password) {
      setError("Todos los campos son obligatorios");
      return;
    }

    setLoading(true);
    setError(null);

    try {
      sessionStorage.removeItem("token");
      sessionStorage.removeItem("cliente");

      const response = await fetch("api/frontend/v1/login", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "Accept": "application/json"
        },
        body: JSON.stringify(form)
      });

      const data = await response.json();

      if (!response.ok) {
        throw new Error(data.message || "Credenciales incorrectas");
      }
      sessionStorage.setItem("token", data.token);
      sessionStorage.setItem("cliente", JSON.stringify(data.cliente));
      window.dispatchEvent(new Event("authChange"));

      navigate("/");

    } catch (err) {
      setError(err.message || "Error de conexión");
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="auth-page-wrapper">
      <div className="login-card">
        <div className="auth-header">
          <h2>¡Hola de nuevo!</h2>
          <p>Ingresa tus datos para continuar comprando</p>
        </div>

        <form onSubmit={handleSubmit} className="auth-form">
          <div className="input-group">
            <label>Correo electrónico</label>
            <input 
              type="email" 
              name="email" 
              placeholder="ejemplo@correo.com"
              value={form.email} 
              onChange={handleChange} 
              required 
            />
          </div>

          <div className="input-group">
            <label>Contraseña</label>
            <input 
              type="password" 
              name="password" 
              placeholder="••••••••"
              value={form.password} 
              onChange={handleChange} 
              required 
            />
          </div>

          {error && <div className="auth-error">{error}</div>}

          <button type="submit" className="btn-auth" disabled={loading}>
            {loading ? <span className="spinner-small"></span> : "Ingresar"}
          </button>

          <div className="auth-footer">
            ¿No tienes una cuenta? <Link to="/registro">Regístrate gratis</Link>
          </div>
        </form>
      </div>
    </div>
  );
}