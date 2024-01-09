import React, { useState } from 'react';
import './style.css'; // Import arkusza stylów CSS

const RestaurantApp = () => {
  const [cart, setCart] = useState([]);
  const [formData, setFormData] = useState({
    name: '',
    address: '',
    phone: '',
    notes: ''
  });

  const menuItems = [
    { id: 1, name: 'Pizza', price: 10 },
    { id: 2, name: 'Burger', price: 8 },
    { id: 3, name: 'Salad', price: 6 }
  ];

  const addToCart = (menuItem) => {
    const updatedCart = [...cart, menuItem];
    setCart(updatedCart);
  };

  const removeFromCart = (index) => {
    const updatedCart = [...cart];
    updatedCart.splice(index, 1);
    setCart(updatedCart);
  };

  const handleInputChange = (e) => {
    const { name, value } = e.target;
    setFormData({
      ...formData,
      [name]: value
    });
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    // Obsługa przesłania formularza (np. wysłanie zamówienia)
    console.log('Zamówienie wysłane:', { formData, cart });
  };

  return (
    <div className="restaurant-app">
      <h1>Restauracja XYZ</h1>
      <div className="menu">
        <h2>Menu</h2>
        <ul>
          {menuItems.map((menuItem) => (
            <li key={menuItem.id}>
              {menuItem.name} - ${menuItem.price}
              <button onClick={() => addToCart(menuItem)}>Dodaj do koszyka</button>
            </li>
          ))}
        </ul>
      </div>
      <div className="cart">
        <h2>Koszyk</h2>
        <ul>
          {cart.map((item, index) => (
            <li key={index}>
              {item.name} - ${item.price}
              <button onClick={() => removeFromCart(index)}>Usuń</button>
            </li>
          ))}
        </ul>
      </div>
      <div className="order-form">
        <h2>Formularz zamówienia</h2>
        <form onSubmit={handleSubmit}>
          <label>
            Imię i nazwisko:
            <input type="text" name="name" value={formData.name} onChange={handleInputChange} />
          </label>
          <label>
            Adres dostawy:
            <input type="text" name="address" value={formData.address} onChange={handleInputChange} />
          </label>
          <label>
            Numer telefonu:
            <input type="text" name="phone" value={formData.phone} onChange={handleInputChange} />
          </label>
          <label>
            Uwagi do zamówienia:
            <textarea name="notes" value={formData.notes} onChange={handleInputChange} />
          </label>
          <button type="submit">Złóż zamówienie</button>
        </form>
      </div>
    </div>
  );
};

export default RestaurantApp;
