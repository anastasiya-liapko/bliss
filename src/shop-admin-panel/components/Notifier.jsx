import React from 'react';
import OverlayTrigger from 'react-bootstrap/OverlayTrigger';
import Popover from 'react-bootstrap/Popover';

function Notifier() {
  return (
    <OverlayTrigger
      trigger="hover"
      placement="bottom"
      delay={{ show: 250, hide: 400 }}
      overlay={
        <Popover>
          Уведомлений нет.
        </Popover>
      }>
      <div className="notifier">
        <i className="far fa-bell"/>
      </div>
    </OverlayTrigger>
  );
}

export default Notifier;
