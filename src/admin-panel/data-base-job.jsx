import React from 'react';
import ReactDOM from 'react-dom';
import DataBaseJob from './components/DataBaseJob.jsx';
import './index.css';

const pageDataBaseJob = document.getElementById('page-data-base-job');

if (pageDataBaseJob) {
  ReactDOM.render(<DataBaseJob/>, pageDataBaseJob);
}
