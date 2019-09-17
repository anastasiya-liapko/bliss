import React, { Component, Fragment } from 'react';
import Modal from 'react-bootstrap/Modal';

class ModalIssue extends Component {
  constructor(props) {
    super(props);

    this.state = {
      error: null,
      isLoading: false,
      showWarning: false,
      showResult: false,
    };

    this.issueOrder = this.issueOrder.bind(this);
    this.handleIssueOrder = this.handleIssueOrder.bind(this);
    this.handleShowWarning = this.handleShowWarning.bind(this);
    this.handleHideModal = this.handleHideModal.bind(this);
  }

  issueOrder(orderId) {
    const { url } = this.props;
    const formData = new FormData();

    formData.append('id', orderId);

    fetch(url, {
      method: 'POST',
      headers: new Headers({
        'X-Requested-With': 'XMLHttpRequest',
      }),
      body: formData,
    })
      .then(response => response.json())
      .then(
        (result) => {
          this.setState({ isLoading: false, showResult: true });

          if (!result.data.success) {
            this.setState({
              error: { message: 'Не удалось выполнить действие. Попробуйте позже.' },
            });
          }
        },
        (error) => {
          this.setState({ error, isLoading: false, showResult: true });
        },
      );
  }

  handleIssueOrder(event) {
    event.target.blur();

    const { orderId } = this.props;

    this.setState({
      isLoading: true,
    }, () => {
      this.issueOrder(orderId);
    });
  }

  handleShowWarning(event) {
    event.target.blur();
    this.setState({ showWarning: true });
  }

  handleHideModal() {
    const { showWarning, showResult } = this.state;

    this.props.onHide();

    if (showWarning) {
      this.setState({ showWarning: false });
    }

    if (showResult) {
      setTimeout(() => {
        this.setState({
          showResult: false,
        }, () => {
          this.props.onFinish();
        });
      }, 500);
    }
  }

  renderModalInner() {
    const {
      error,
      isLoading,
      showWarning,
      showResult,
    } = this.state;

    const {
      title,
      titleWarning,
      textWarning,
      titleResult,
      textResult,
    } = this.props;

    if (error) {
      return (
        <Fragment>
          <Modal.Header closeButton>
            <Modal.Title id="issueModalLabel">Ошибка</Modal.Title>
          </Modal.Header>
          <Modal.Body>
            <p>{error.message}</p>
          </Modal.Body>
          <Modal.Footer>
            <button
              type="button"
              className="btn btn-primary"
              onClick={this.handleHideModal}>
              Ок
            </button>
          </Modal.Footer>
        </Fragment>
      );
    }

    if (showResult) {
      return (
        <Fragment>
          <Modal.Header closeButton>
            <Modal.Title id="issueModalLabel">{titleResult}</Modal.Title>
          </Modal.Header>
          <Modal.Body>
            <p>{textResult}</p>
          </Modal.Body>
          <Modal.Footer>
            <button
              type="button"
              className="btn btn-primary"
              onClick={this.handleHideModal}>
              Ок
            </button>
          </Modal.Footer>
        </Fragment>
      );
    }

    if (showWarning) {
      return (
        <Fragment>
          <Modal.Header closeButton>
            <Modal.Title id="issueModalLabel">{titleWarning}</Modal.Title>
          </Modal.Header>
          <Modal.Body>
            <p>{textWarning}</p>
          </Modal.Body>
          <Modal.Footer>
            <button
              type="button"
              className="btn btn-primary"
              onClick={this.handleIssueOrder}>
              Продолжить {isLoading && <i className="fas fa-sync fa-spin" aria-hidden={true}/>}
            </button>
            <button
              type="button"
              className="btn btn-secondary"
              onClick={this.handleHideModal}>
              Отмена
            </button>
          </Modal.Footer>
        </Fragment>
      );
    }

    return (
      <Fragment>
        <Modal.Header closeButton>
          <Modal.Title id="issueModalLabel">{title}</Modal.Title>
        </Modal.Header>
        <Modal.Footer>
          <button
            type="button"
            className="btn btn-primary"
            onClick={this.handleShowWarning}>
            Да
          </button>
          <button
            type="button"
            className="btn btn-secondary"
            onClick={this.handleHideModal}>
            Отмена
          </button>
        </Modal.Footer>
      </Fragment>
    );
  }

  render() {
    const { show } = this.props;

    return (
      <Modal
        show={show}
        onHide={this.handleHideModal}
        aria-labelledby="issueModalLabel"
        centered>
        {this.renderModalInner()}
      </Modal>
    );
  }
}

export default ModalIssue;
