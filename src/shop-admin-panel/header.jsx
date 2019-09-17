import React from 'react';
import ReactDOM from 'react-dom';
import Notifier from './components/Notifier.jsx';

const logoutWrap = document.querySelector('.logout-wrap');

if (logoutWrap) {
  const shopAdminHeader = document.createElement('div');
  shopAdminHeader.id = 'shopAdminHeader';

  logoutWrap.insertBefore(shopAdminHeader, logoutWrap.firstChild);

  ReactDOM.render(<Notifier/>, shopAdminHeader);
}
