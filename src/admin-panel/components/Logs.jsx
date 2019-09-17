import React from 'react';
import Helper from './Helper.jsx';

function Logs() {
  const downloadUrl = `${Helper.getLocation()}/admin-panel/logs/download-logs`;

  return (
    <div className="card">
      <div className="card-body">
        <h5 className="card-title">Архив содержит следующие директории:</h5>
        <ul className="card-text">
          <li>mail — запросы к сервису отправки электронной почты;</li>
          <li>mfi — запросы к МФО;</li>
          <li>requests — запросы интернет-магазинов к странице /phone-number;</li>
          <li>sms.ru — запросы к сервису отправки смс-сообщений;</li>
          <li>telegram — запросы к Telegram;</li>
        </ul>
        <a href={downloadUrl} className="btn btn-primary" download>Скачать логи</a>
      </div>
    </div>
  );
}

export default Logs;
