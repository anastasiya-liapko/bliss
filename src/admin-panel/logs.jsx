import React from 'react';
import ReactDOM from 'react-dom';
import Logs from './components/Logs.jsx';
import './index.css';

const pageLogs = document.getElementById('page-logs');

if (pageLogs) {
  ReactDOM.render(<Logs/>, pageLogs);
}
