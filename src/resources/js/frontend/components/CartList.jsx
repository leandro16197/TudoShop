import { useCart } from "../context/CartContext";

export default function Navbar() {
  const { cart } = useCart();

  return (
    <nav>
      <span>Items en carrito: {cart.length}</span>

      <div className="cart-dropdown">
        {cart.length === 0 ? (
          <p>El carrito está vacío</p>
        ) : (
          cart.map((item) => (
            <div key={item.id} className="cart-item">
              <img src={item.imagen} alt={item.nombre} width="50" />
              <div>
                <p>{item.nombre}</p>
                <p>{item.cantidad} x ${item.precio}</p>
              </div>
            </div>
          ))
        )}
      </div>
    </nav>
  );
}