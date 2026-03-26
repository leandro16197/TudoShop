import { useState,useEffect } from "react";
import ReactMarkdown from "react-markdown"; 


export default function Chatbot() {
    const [messages, setMessages] = useState([]);
    const [input, setInput] = useState("");
    const [open, setOpen] = useState(false);
    
    useEffect(() => {
        if (open && messages.length === 0) {
            setMessages([{ 
                text: "¡Hola! 👋 Soy tu asistente de ShopTudo. ¿Qué producto estás buscando hoy?", 
                type: "bot" 
            }]);
        }
    }, [open]);

    const enviarMensaje = async () => {
        if (!input.trim()) return;
        
        const userMessage = { text: input, type: "user" };
        setMessages((prev) => [...prev, userMessage]);
        
        const currentInput = input; 
        setInput("");

        try {
            const res = await fetch("/api/frontend/v1/chatbot", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json"
                },
                body: JSON.stringify({ message: currentInput })
            });

            const data = await res.json();
            
            setMessages((prev) => [
                ...prev,
                { 
                    text: data.message || "No recibí respuesta.", 
                    type: "bot", 
                    products: data.products || [] 
                }
            ]);
        } catch (error) {
            setMessages((prev) => [
                ...prev,
                { text: "Lo siento, no pude conectar con el asistente.", type: "bot", products: [] }
            ]);
        }
    };

    return (
        <>
            <button className="chatbot-button" onClick={() => setOpen(true)}>
                <img src="/images/ShopTudoChat.png" alt="Chatbot ShopTudo" className="chatbot-icon" />
            </button>
            {open && (
                <div className="chatbot-overlay">
                    <div className="chatbot-modal">
                        <div className="chatbot-header">
                            TudoBot
                            <button className="close" onClick={() => setOpen(false)}>✖</button>
                        </div>

                        <div className="chatbot-messages">
                            <div className="chatbot-messages">
                                {messages.map((m, i) => (
                                    <div key={i} className={`msg ${m.type}`}>
                                        {m.text && <ReactMarkdown>{m.text}</ReactMarkdown>}
                                        {m.products && m.products.length > 0 && (
                                            <div className="custom-product-list">
                                                {m.products.map((p, idx) => (
                                                    <div key={idx} className="my-product-card">
                                                        <div className="product-info">
                                                            <span className="product-name">{p.name}</span>
                                                            <span className="product-price">${Number(p.price).toFixed(2)}</span>
                                                        </div>
                                                        <button 
                                                            className="view-details-btn"
                                                            onClick={() => window.open(`/productos/${p.id}`, '_blank')}
                                                        >
                                                            Ver detalles
                                                        </button>
                                                    </div>
                                                ))}
                                            </div>
                                        )}
                                    </div>
                                ))}
                            </div>
                        </div>

                        <div className="chatbot-input">
                            <input
                                value={input}
                                onChange={(e) => setInput(e.target.value)}
                                onKeyDown={(e) => { if (e.key === "Enter") enviarMensaje(); }}
                                placeholder="Escribí tu consulta..."
                            />
                            <button onClick={enviarMensaje}>Enviar</button>
                        </div>
                    </div>
                </div>
            )}
        </>
    );
}