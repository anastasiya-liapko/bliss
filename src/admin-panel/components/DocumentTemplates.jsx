import React, { Fragment } from 'react';
import DownloadTemplatesCard from './DownloadTemplatesCard.jsx';
import QuestionnaireFl115ForEntrepreneurCard from './QuestionnaireFl115ForEntrepreneurCard.jsx';
import QuestionnaireFl115ForLlcCard from './QuestionnaireFl115ForLlcCard.jsx';
import ContractCard from './ContractCard.jsx';
import JoiningApplicationForEntrepreneurCard from './JoiningApplicationForEntrepreneurCard.jsx';
import JoiningApplicationForLlcCard from './JoiningApplicationForLlcCard.jsx';

function DocumentTemplates() {
  return (
    <Fragment>
      <DownloadTemplatesCard/>
      <QuestionnaireFl115ForEntrepreneurCard/>
      <QuestionnaireFl115ForLlcCard/>
      <ContractCard/>
      <JoiningApplicationForEntrepreneurCard/>
      <JoiningApplicationForLlcCard/>
    </Fragment>
  );
}

export default DocumentTemplates;
