import React from 'react';
import Helper from './Helper.jsx';
import UploadTemplateForm from './UploadTemplateForm.jsx';

function QuestionnaireFl115ForEntrepreneurCard() {
  const uploadTemplateUrl = `${Helper.getLocation()}/admin-panel/document-templates
    /upload-document-questionnaire-for-entrepreneur`;

  return (
    <div className="card mb-4">
      <div className="card-body">
        <h5 className="card-title">Обновить анкету ФЗ 115 для ИП</h5>
        <p className="card-text">В документе можно использовать только следующие переменные:</p>
        <ul className="card-text">
          <li>$&shy;&#123;tin&#125; — ИНН;</li>
          <li>$&shy;&#123;bin&#125; — ОГРН;</li>
          <li>$&shy;&#123;phone&#125; — номер телефона;</li>
          <li>$&shy;&#123;email&#125; — email;</li>
          <li>$&shy;&#123;license_type&#125; — тип лицензии;</li>
          <li>$&shy;&#123;license_number&#125; — номер лицензии;</li>
          <li>$&shy;&#123;boss_full_name&#125; — ФИО руководителя;</li>
          <li>$&shy;&#123;date&#125; — текущая дата;</li>
          <li>$&shy;&#123;boss_name&#125; — фамилия и инициалы руководителя;</li>
          <li>$&shy;&#123;boss_birth_date&#125; — дата рождения руководителя;</li>
          <li>$&shy;&#123;boss_birth_place&#125; — место рождения руководителя;</li>
          <li>$&shy;&#123;boss_passport_number&#125; — серия и номер паспорта
            руководителя;
          </li>
          <li>$&shy;&#123;boss_passport_issued_date&#125; — дата выдачи паспорта руководителя;
          </li>
          <li>$&shy;&#123;boss_passport_issued_by&#125; — кем выдан паспорт руководителя;
          </li>
          <li>$&shy;&#123;boss_passport_division_code&#125; — код подразделения, выдавшего
            паспорт руководителя;
          </li>
          <li>$&shy;&#123;registration_address&#125; — адрес регистрации руководителя.</li>
        </ul>
        <p className="card-text">Вставляйте в документ переменные, оборачивая названия в фигурные
          скобки, как указано выше.</p>
        <p className="card-text">Загружать можно только в формате docx, другие форматы приниматься
          и обрабатываться не будут.</p>
        <UploadTemplateForm
          id="documentQuestionnaireFl115ForEntrepreneur"
          name="document_questionnaire_fl_115_for_entrepreneur"
          label="Анкета ФЗ 115 для ИП"
          url={uploadTemplateUrl}/>
      </div>
    </div>
  );
}

export default QuestionnaireFl115ForEntrepreneurCard;
