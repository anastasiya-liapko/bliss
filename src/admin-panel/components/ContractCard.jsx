import React from 'react';
import Helper from './Helper.jsx';
import UploadTemplateForm from './UploadTemplateForm.jsx';

function ContractCard() {
  const uploadTemplateUrl = `${Helper.getLocation()}/admin-panel/document-templates
    /upload-document-contract`;

  return (
    <div className="card mb-4">
      <div className="card-body">
        <h5 className="card-title">Обновить соглашение</h5>
        <p className="card-text">В документе можно использовать только следующие переменные:</p>
        <ul className="card-text">
          <li>$&shy;&#123;date&#125; — текущая дата;</li>
          <li>$&shy;&#123;organization_name&#125; — название организации;</li>
          <li>$&shy;&#123;boss_full_name&#125; — ФИО руководителя;</li>
          <li>$&shy;&#123;email&#125; — email;</li>
          <li>$&shy;&#123;bin&#125; — ОГРН;</li>
          <li>$&shy;&#123;tin&#125; — ИНН;</li>
          <li>$&shy;&#123;fact_address&#125; — фактический адрес;</li>
          <li>$&shy;&#123;settlement_account&#125; — расчётный счёт;</li>
          <li>$&shy;&#123;correspondent_account&#125; — корреспондентский счёт;</li>
          <li>$&shy;&#123;bik&#125; — БИК банка;</li>
          <li>$&shy;&#123;bank_name&#125; — наименование банка;</li>
          <li>$&shy;&#123;boss_name&#125; — фамилия и инициалы руководителя;</li>
          <li>$&shy;&#123;phone&#125; — номер телефона;</li>
          <li>$&shy;&#123;boss_position&#125; — должность руководителя;</li>
          <li>$&shy;&#123;boss_basis_acts_full_info&#125; — документ, но основании которого
            действует руководитель;
          </li>
          <li>$&shy;&#123;address&#125; — юридический адрес / фактический адрес.</li>
        </ul>
        <p className="card-text">Вставляйте в документ переменные, оборачивая названия в фигурные
          скобки, как указано выше.</p>
        <p className="card-text">Загружать можно только в формате docx, другие форматы приниматься
          и обрабатываться не будут.</p>
        <UploadTemplateForm
          id="documentContract"
          name="document_contract"
          label="Соглашение"
          url={uploadTemplateUrl}/>
      </div>
    </div>
  );
}

export default ContractCard;
