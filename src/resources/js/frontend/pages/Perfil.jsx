import React, { useState, useEffect } from 'react';

export default function Perfil() {
    const [user, setUser] = useState({
        nombre: '',
        apellido: '',
        email: '',
        password: '',
        password_confirmation: '' // Cambiado de email a password
    });
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        const fetchUserData = async () => {
            const token = localStorage.getItem("token");
            try {
                const response = await fetch('api/frontend/v1/perfil', {
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    }
                });
                const data = await response.json();
                
                if (response.ok) {
                    setUser({
                        nombre: data.nombre || '',
                        apellido: data.apellido || '',
                        email: data.email || '',
                        password: '',
                        password_confirmation: '' 
                    });
                }
            } catch (error) {
                console.error("Error al obtener datos:", error);
            } finally {
                setLoading(false);
            }
        };
        fetchUserData();
    }, []);

    const handleChange = (e) => {
        setUser({ ...user, [e.target.name]: e.target.value });
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        
        if (user.password && user.password !== user.password_confirmation) {
            alert("Las contraseñas no coinciden.");
            return;
        }

        const token = localStorage.getItem("token");
        try {
            const response = await fetch('api/frontend/v1/perfil/actualizar', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${token}`
                },
                body: JSON.stringify(user)
            });

            if (response.ok) {
                const clienteStorage = JSON.parse(localStorage.getItem("cliente"));
                localStorage.setItem("cliente", JSON.stringify({ ...clienteStorage, nombre: user.nombre }));
                window.dispatchEvent(new Event("authChange"));
                
                setUser(prev => ({ ...prev, password: '', password_confirmation: '' }));
            }
        } catch (error) {
            alert("Error al actualizar");
        }
    };

    if (loading) {
        return (
            <div className="loading-container">
                <div className="spinner"></div>
                <p>Cargando tu perfil...</p>
            </div>
        );
    }

    return (
        <div className="perfil-container">
            {/* SIDEBAR (Igual que antes) */}
            <aside className="perfil-sidebar">
                <div className="sidebar-filter-card"> 
                    <p className="filter-title">Mi Cuenta</p>
                    <div className="sidebar-header">
                        <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="Avatar" className="profile-avatar" />
                        <div className="user-info-display">
                            <span className="label">Nombre</span>
                            <p className="value">{user.nombre}</p>
                            <span className="label">Apellido</span>
                            <p className="value">{user.apellido || '-'}</p>
                        </div>
                    </div>
                    <div className="filter-separator"></div>
                    <nav className="sidebar-nav">
                        <button className="nav-item active">👤 Perfil</button>
                        <button className="nav-item">📦 Mis Pedidos</button>
                    </nav>
                </div>
            </aside>

            {/* FORMULARIO ACTUALIZADO */}
            <main className="perfil-content">
                <div className="form-card">
                    <h2>Configuración de la Cuenta</h2>
                    <p className="subtitle">Gestione sus datos personales y seguridad</p>
                    
                    <form onSubmit={handleSubmit} className="perfil-form">
                        <div className="form-row">
                            <div className="form-group">
                                <label>Nombre</label>
                                <input type="text" name="nombre" value={user.nombre} onChange={handleChange} required />
                            </div>
                            <div className="form-group">
                                <label>Apellido</label>
                                <input type="text" name="apellido" value={user.apellido} onChange={handleChange} required />
                            </div>
                        </div>

                        <div className="form-group">
                            <label>Email (No editable)</label>
                            <input type="email" value={user.email} disabled className="input-disabled" />
                        </div>

                        <div className="form-row">
                            <div className="form-group">
                                <label>Nueva Contraseña</label>
                                <input 
                                    type="password" 
                                    name="password" 
                                    placeholder="Dejar en blanco para no cambiar" 
                                    value={user.password} 
                                    onChange={handleChange} 
                                />
                            </div>
                            <div className="form-group">
                                <label>Confirmar Contraseña</label>
                                <input 
                                    type="password" 
                                    name="password_confirmation" 
                                    placeholder="Repite la nueva contraseña" 
                                    value={user.password_confirmation} 
                                    onChange={handleChange} 
                                />
                            </div>
                        </div>

                        <button type="submit" className="btn-save">ACTUALIZAR DATOS</button>
                    </form>
                </div>
            </main>
        </div>
    );
}