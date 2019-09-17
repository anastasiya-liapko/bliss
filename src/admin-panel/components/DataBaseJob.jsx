import React, { Fragment } from 'react';
import CleanDataBaseForm from './CleanDataBaseForm.jsx';
import UpdateDataBaseForm from './UpdateDataBaseForm.jsx';
import Helper from './Helper.jsx';

function DataBaseJob() {
  return (
    <Fragment>
      <div className="card mb-4">
        <div className="card-body">
          <h5 className="card-title">Очистка базы данных</h5>
          <p className="card-text">Очистка просроченных токенов и неакутальных данных.</p>
          <CleanDataBaseForm url={`${Helper.getLocation()}/admin-panel/data-base-job/clean-up`}/>
        </div>
      </div>
      <div className="card mb-4">
        <div className="card-body">
          <h5 className="card-title">Обновление базы данных</h5>
          <UpdateDataBaseForm
            hasNotCompletedMigrationsUrl={`${Helper.getLocation()}/admin-panel/data-base-job/has-not-completed-migrations`}
            updateUrl={`${Helper.getLocation()}/admin-panel/data-base-job/update`}/>
        </div>
      </div>
    </Fragment>
  );
}

export default DataBaseJob;
