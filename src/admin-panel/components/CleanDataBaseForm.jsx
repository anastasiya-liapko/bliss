import React, { Component, Fragment } from 'react';

class CleanDataBaseForm extends Component {
  constructor(props) {
    super(props);

    this.state = {
      message: '',
      showResult: false,
      isLoading: false,
    };

    this.cleanDataBase = this.cleanDataBase.bind(this);
    this.handleFormSubmit = this.handleFormSubmit.bind(this);
  }

  cleanDataBase() {
    const { url } = this.props;

    this.setState({ isLoading: true });

    fetch(url, {
      method: 'POST',
      headers: new Headers({
        'X-Requested-With': 'XMLHttpRequest',
      }),
    })
      .then(response => response.json())
      .then(
        (result) => {
          const message = result.data.success ? 'База данных очищена.' : 'Не удалось очистить базу данных.';

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

  handleFormSubmit(event) {
    event.preventDefault();
    event.stopPropagation();

    this.cleanDataBase();
  }

  render() {
    const {
      message,
      showResult,
      isLoading,
    } = this.state;

    if (showResult) {
      return (
        <p>{message}</p>
      );
    }

    return (
      <Fragment>
        <form
          noValidate={true}
          onSubmit={this.handleFormSubmit}>
          <div className="form-group">
            <button
              type="submit"
              className="btn btn-primary"
              disabled={isLoading}>
              {isLoading ? 'Подождите' : 'Очистить'}
            </button>
          </div>
        </form>
      </Fragment>
    );
  }
}

export default CleanDataBaseForm;
