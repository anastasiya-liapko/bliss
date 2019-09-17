import React from 'react';
import ReactDOM from 'react-dom';
import IncomingOrders from './components/IncomingOrders.jsx';
import './index.css';

const pageIncomingOrders = document.getElementById('page-incoming-orders');

if (pageIncomingOrders) {
  ReactDOM.render(<IncomingOrders perPage={10}/>, pageIncomingOrders);
}
