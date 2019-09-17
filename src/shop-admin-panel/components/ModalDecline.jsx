import React, { Component, Fragment } from 'react';
import Modal from 'react-bootstrap/Modal';

class ModalDecline extends Component {
  constructor(props) {
    super(props);

    this.state = {
      error: null,
      isLoading: false,
      showResult: false,
    };

    this.declineOrder = this.declineOrder.bind(this);
    this.handleDeclineOrder = this.handleDeclineOrder.bind(this);
    this.handleHideModal = this.handleHideModal.bind(this);
  }

  declineOrder(orderId) {
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

  handleDeclineOrder(event) {
    event.target.blur();

    const { orderId } = this.props;

    this.setState({
      isLoading: true,
    }, () => {
      this.declineOrder(orderId);
    });
  }

  handleHideModal() {
    const { showResult } = this.state;

    this.props.onHide();

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
    const { error, isLoading, showResult } = this.state;
    const { title, titleResult, textResult } = this.props;

    if (error) {
      return (
        <Fragment>
          <Modal.Header closeButton>
            <Modal.Title id="declineModalLabel">Ошибка</Modal.Title>
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
            <Modal.Title id="declineModalLabel">{titleResult}</Modal.Title>
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

    return (
      <Fragment>
        <Modal.Header closeButton>
          <Modal.Title id="declineModalLabel">{title}</Modal.Title>
        </Modal.Header>
        <Modal.Footer>
          <button
            type="button"
            className="btn btn-primary"
            onClick={this.handleDeclineOrder}>
            Да {isLoading && <i className="fas fa-sync fa-spin" aria-hidden={true}/>}
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
        aria-labelledby="declineModalLabel"
        centered>
        {this.renderModalInner()}
      </Modal>
    );
  }
}

export default ModalDecline;
