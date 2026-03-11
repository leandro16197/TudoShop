import { useState } from "react";
import ReactMarkdown from "react-markdown"; 

export default function Chatbot() {
    const [messages, setMessages] = useState([]);
    const [input, setInput] = useState("");
    const [open, setOpen] = useState(false);

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
            <button className="chatbot-button" onClick={() => setOpen(true)}>💬</button>
            {open && (
                <div className="chatbot-overlay">
                    <div className="chatbot-modal">
                        <div className="chatbot-header">
                            TudoBot
                            <button className="close" onClick={() => setOpen(false)}>✖</button>
                        </div>

                        <div className="chatbot-messages">
                            {messages.map((m, i) => {
                                const lines = m.text.split('\n');
                                const greeting = lines[0];
                                const productList = lines.slice(1); 

                                return (
                                    <div key={i} className={`msg ${m.type}`}>
                                        <ReactMarkdown>{greeting}</ReactMarkdown>
                                        <div className="custom-product-list">
                                            {productList.map((line, idx) => {
                                                const nombreMatch = line.match(/\*\*Nombre\*\*:\s*(.*?)(?=\s*\||$)/);
                                                const nombre = nombreMatch ? nombreMatch[1].trim() : 'Producto';
                                                const precioMatch = line.match(/\*\*Precio\*\*:\s*([\d.]+)/);
                                                const precio = precioMatch ? precioMatch[1] : '0.00';
                                                const idMatch = line.match(/\*\*ID\*\*:\s*(\d+)/);
                                                const id = idMatch ? idMatch[1] : null;
                                                if (!nombre) return null;
                                                return (
                                                    <div key={idx} className="my-product-card">
                                                        <span><strong>{nombre}</strong> - ${precio}</span>
                                                        <button onClick={() => window.open(`/productos/${id}`, '_blank', 'noopener,noreferrer')}>
                                                            Ver detalles
                                                        </button>
                                                    </div>
                                                );
                                            })}
                                        </div>
                                    </div>
                                );
                            })}
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