import React from 'react';
import ReactDOM from 'react-dom';
import DocumentTemplates from './components/DocumentTemplates.jsx';
import './index.css';

const pageDocumentTemplates = document.getElementById('page-document-templates');

if (pageDocumentTemplates) {
  ReactDOM.render(<DocumentTemplates/>, pageDocumentTemplates);
}
