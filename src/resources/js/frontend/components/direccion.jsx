const StepAddress = ({ shippingType, setShippingType, onCalculate }) => {
  const [cp, setCp] = useState('');

  const handleBlurCp = async () => {
    if (cp.length >= 4) {
      const res = await axios.post('/api/calcular-envio', { cp_cliente: cp, subtotal: 182 });
      onCalculate(res.data);
    }
  };

  return (
    <div className="ps-4">
      <div className="d-flex gap-3 mb-4">
        <div 
          className={`flex-fill p-3 border rounded-3 text-center cursor-pointer ${shippingType === 'domicilio' ? 'border-success bg-light' : ''}`}
          onClick={() => setShippingType('domicilio')}
        >
          <input type="radio" checked={shippingType === 'domicilio'} readOnly /> Envío a domicilio
        </div>
        <div 
          className={`flex-fill p-3 border rounded-3 text-center cursor-pointer ${shippingType === 'sucursal' ? 'border-success bg-light' : ''}`}
          onClick={() => setShippingType('sucursal')}
        >
          <input type="radio" checked={shippingType === 'sucursal'} readOnly /> Retiro en sucursal
        </div>
      </div>

      <div className="row g-3">
        <div className="col-md-6">
          <input type="text" className="form-control" placeholder="CP *" 
                 value={cp} onChange={(e) => setCp(e.target.value)} onBlur={handleBlurCp} />
        </div>
        <div className="col-md-6">
          <input type="text" className="form-control" placeholder="Localidad *" />
        </div>
        <div className="col-12">
          <input type="text" className="form-control" placeholder="Calle *" />
        </div>
      </div>
      
      <button className="btn btn-success mt-4 px-5 fw-bold py-2">Guardar</button>
    </div>
  );
};