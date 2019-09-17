import React from 'react';
import Helper from './Helper.jsx';
import UploadTemplateForm from './UploadTemplateForm.jsx';

function JoiningApplicationForLlcCard() {
  const uploadTemplateUrl = `${Helper.getLocation()}/admin-panel/document-templates
    /upload-document-joining-application-for-llc`;

  return (
    <div className="card mb-4">
      <div className="card-body">
        <h5 className="card-title">Обновить заявление о присоединении для ООО</h5>
        <p className="card-text">В документе можно использовать только следующие переменные:</p>
        <ul className="card-text">
          <li>$&shy;&#123;date&#125; — текущая дата;</li>
          <li>$&shy;&#123;organization_name&#125; — название организации;</li>
          <li>$&shy;&#123;tin&#125; — ИНН;</li>
          <li>$&shy;&#123;bin&#125; — ОГРН;</li>
          <li>$&shy;&#123;fact_address&#125; — фактический адрес;</li>
          <li>$&shy;&#123;phone&#125; — номер телефона;</li>
          <li>$&shy;&#123;email&#125; — email;</li>
          <li>$&shy;&#123;settlement_account&#125; — расчётный счёт;</li>
          <li>$&shy;&#123;correspondent_account&#125; — корреспондентский счёт;</li>
          <li>$&shy;&#123;bik&#125; — БИК банка;</li>
          <li>$&shy;&#123;bank_name&#125; — наименование банка;</li>
          <li>$&shy;&#123;boss_name&#125; — фамилия и инициалы руководителя;</li>
          <li>$&shy;&#123;boss_full_name&#125; — ФИО руководителя;</li>
          <li>$&shy;&#123;boss_basis_acts_full_info&#125; — документ, но основании которого
            действует руководитель;
          </li>
          <li>$&shy;&#123;legal_address&#125; — юридический адрес;</li>
          <li>$&shy;&#123;cio&#125; — КПП.</li>
        </ul>
        <p className="card-text">Вставляйте в документ переменные, оборачивая названия в фигурные скобки, как указано
          выше.</p>
        <p className="card-text">Загружать можно только в формате docx, другие форматы приниматься и обрабатываться не
          будут.</p>
        <UploadTemplateForm
          id="documentJoiningApplicationForLlc"
          name="document_joining_application_for_llc"
          label="Заявление о присоединении для ООО"
          url={uploadTemplateUrl}/>
      </div>
    </div>
  );
}

export default JoiningApplicationForLlcCard;
