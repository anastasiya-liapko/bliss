import React, { Component, Fragment } from 'react';

class UpdateDataBaseForm extends Component {
  constructor(props) {
    super(props);

    this.state = {
      message: '',
      hasNotCompletedMigration: false,
      showResult: false,
      isLoading: false,
    };

    this.updateDataBase = this.updateDataBase.bind(this);
    this.handleFormSubmit = this.handleFormSubmit.bind(this);
  }

  componentDidMount() {
    this.hasDataBaseMigrations();
  }

  hasDataBaseMigrations() {
    const { hasNotCompletedMigrationsUrl } = this.props;

    fetch(hasNotCompletedMigrationsUrl, {
      method: 'GET',
      headers: new Headers({
        'X-Requested-With': 'XMLHttpRequest',
      }),
    })
      .then(response => response.json())
      .then(
        /**
         * @param result.data.has_not_completed_db_migrations
         */
        (result) => {
          console.log(result.data.has_not_completed_db_migrations);
          this.setState({ hasNotCompletedMigration: result.data.has_not_completed_db_migrations });
        },
        (error) => {
          this.setState({
            message: `Ошибка: ${error.message}`,
            showResult: true,
          });
        },
      );
  }

  updateDataBase() {
    const { updateUrl } = this.props;

    this.setState({ isLoading: true });

    fetch(updateUrl, {
      method: 'POST',
      headers: new Headers({
        'X-Requested-With': 'XMLHttpRequest',
      }),
    })
      .then(response => response.json())
      .then(
        (result) => {
          const message = result.data.success ? 'База данных обновлена.' : 'Не удалось обновить базу данных.';

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

    this.updateDataBase();
  }

  render() {
    const {
      message,
      hasNotCompletedMigration,
      showResult,
      isLoading,
    } = this.state;

    if (showResult) {
      return (
        <p>{message}</p>
      );
    }

    if (!hasNotCompletedMigration) {
      return (
        <p>Обновление не требуется.</p>
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
              {isLoading ? 'Подождите' : 'Обновить'}
            </button>
          </div>
        </form>
      </Fragment>
    );
  }
}

export default UpdateDataBaseForm;
