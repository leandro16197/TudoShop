const ResumenCompra = ({ datos }) => {
    return (
        <div className="card border-0 shadow-sm" style={{ borderRadius: '15px' }}>
            <div className="card-body p-4">
                <h6 className="fw-bold mb-4">Resumen de compra</h6>
                
                <div className="d-flex justify-content-between mb-2 small text-muted">
                    <span>Subtotal:</span>
                    <span>${datos.subtotal.toLocaleString()}</span>
                </div>
                <div className="d-flex justify-content-between mb-2 small text-muted">
                    <span>Costo de envío:</span>
                    <span className={datos.costoEnvio === 0 ? 'text-success' : ''}>
                        {datos.costoEnvio === 0 ? 'Gratis' : `$${datos.costoEnvio.toLocaleString()}`}
                    </span>
                </div>
                <hr />
                <div className="d-flex justify-content-between align-items-center">
                    <h5 className="fw-bold mb-0">TOTAL</h5>
                    <h4 className="fw-bold mb-0 text-dark">${datos.total.toLocaleString()}</h4>
                </div>
            </div>
        </div>
    );
};