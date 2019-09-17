import React from 'react';
import Helper from './Helper.jsx';

function DownloadTemplatesCard() {
  const documentTemplatesUrl = `${Helper.getLocation()}/admin-panel/document-templates`;
  const questionnaireFl115ForEntrepreneurUrl = `${documentTemplatesUrl}/download-document-questionnaire-for-entrepreneur`;
  const questionnaireFl115ForLlcUrl = `${documentTemplatesUrl}/download-document-questionnaire-for-llc`;
  const contractUrl = `${documentTemplatesUrl}/download-document-contract`;
  const joiningApplicationForEntrepreneurUrl = `${documentTemplatesUrl}/download-document-joining-application-for-entrepreneur`;
  const joiningApplicationForLlcUrl = `${documentTemplatesUrl}/download-document-joining-application-for-llc`;

  return (
    <div className="card mb-4">
      <div className="card-body">
        <h5 className="card-title">Скачать</h5>
        <ul className="card-text">
          <li><a href={questionnaireFl115ForEntrepreneurUrl} download={true}>Анкета
            ФЗ 115 для ИП</a></li>
          <li><a href={questionnaireFl115ForLlcUrl} download={true}>Анкета ФЗ 115 для ООО</a></li>
          <li><a href={contractUrl} download={true}>Соглашение</a></li>
          <li><a href={joiningApplicationForEntrepreneurUrl} download={true}>Заявление о
            присоединении для ИП</a></li>
          <li><a href={joiningApplicationForLlcUrl} download={true}>Заявление о присоединении
            для ООО</a></li>
        </ul>
      </div>
    </div>
  );
}

export default DownloadTemplatesCard;
