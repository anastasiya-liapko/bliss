import React, { Component, Fragment } from 'react';

class UploadTemplateForm extends Component {
  constructor(props) {
    super(props);

    this.state = {
      label: '',
      message: '',
      showResult: false,
      formSubmitted: false,
      formValidated: false,
      isLoading: false,
    };

    this.validateForm = this.validateForm.bind(this);
    this.uploadFile = this.uploadFile.bind(this);
    this.handleInputChange = this.handleInputChange.bind(this);
    this.handleFormSubmit = this.handleFormSubmit.bind(this);
    this.fileInput = React.createRef();
  }

  componentDidMount() {
    this.setState({ label: this.props.label });
  }

  validateForm(callback = null) {
    this.setState({
      formValidated: !!this.fileInput.current.files.length,
    }, () => {
      if (callback) {
        callback();
      }
    });
  }

  uploadFile() {
    const data = new FormData();
    data.append(this.props.name, this.fileInput.current.files[0]);

    this.setState({ isLoading: true });

    fetch(this.props.url, {
      method: 'POST',
      headers: new Headers({
        'X-Requested-With': 'XMLHttpRequest',
      }),
      body: data,
    })
      .then(response => response.json())
      .then(
        (result) => {
          const message = result.data.success ? 'Шаблон успешно обновлён.' : 'Не удалось обновить шаблон.';

          this.setState({
            isLoading: false,
            message,
            showResult: true,
          });
        },
        (error) => {
          this.setState({
            isLoading: false,
            message: `Ошибка: ${error.message}`,
            showResult: true,
          });
        },
      );
  }

  handleInputChange() {
    const { files } = this.fileInput.current;

    this.setState({
      label: files.length ? files[0].name : this.props.label,
    });

    if (this.state.formSubmitted) {
      this.validateForm();
    }
  }

  handleFormSubmit(event) {
    event.preventDefault();
    event.stopPropagation();

    this.setState({ formSubmitted: true });

    this.validateForm(() => {
      if (this.state.formValidated) {
        this.uploadFile();
      }
    });
  }

  render() {
    const {
      label,
      message,
      showResult,
      formSubmitted,
      formValidated,
      isLoading,
    } = this.state;
    const { id, name } = this.props;

    let validationClass = '';

    if (formSubmitted && !formValidated) {
      validationClass = 'is-invalid';
    } else if (formSubmitted && formValidated) {
      validationClass = 'is-valid';
    }

    return (
      <Fragment>
        <form
          className={validationClass}
          noValidate={true}
          onSubmit={this.handleFormSubmit}>
          <div className="form-group">
            <div className="custom-file">
              <input
                type="file"
                id={id}
                className={`custom-file-input ${validationClass}`}
                name={name}
                accept=".docx"
                ref={this.fileInput}
                onChange={this.handleInputChange}/>
              <label className="custom-file-label" htmlFor={id}>{label}</label>
              <div className="invalid-feedback">Файл не выбран.</div>
            </div>
          </div>
          <div className="form-group">
            <button type="submit" className="btn btn-primary" disabled={isLoading}>Отправить</button>
          </div>
        </form>
        {showResult && <p>{message}</p>}
      </Fragment>
    );
  }
}

export default UploadTemplateForm;
