import React from 'react';
import ReactDOM from 'react-dom';
import DeliveringOrders from './components/DeliveringOrders.jsx';
import './index.css';

const pageDeliveringOrders = document.getElementById('page-delivering-orders');

if (pageDeliveringOrders) {
  ReactDOM.render(<DeliveringOrders perPage={10}/>, pageDeliveringOrders);
}
