import React from 'react';
import ReactDOM from 'react-dom';
import IssuedOrders from './components/IssuedOrders.jsx';
import './index.css';

const pageIssuedOrders = document.getElementById('page-issued-orders');

if (pageIssuedOrders) {
  ReactDOM.render(<IssuedOrders perPage={10}/>, pageIssuedOrders);
}
