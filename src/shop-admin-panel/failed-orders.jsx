import React from 'react';
import ReactDOM from 'react-dom';
import FailedOrders from './components/FailedOrders.jsx';
import './index.css';

const pageFailedOrders = document.getElementById('page-failed-orders');

if (pageFailedOrders) {
  ReactDOM.render(<FailedOrders perPage={10}/>, pageFailedOrders);
}
