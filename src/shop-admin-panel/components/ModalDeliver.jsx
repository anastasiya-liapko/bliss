import React, { Component, Fragment } from 'react';
import Modal from 'react-bootstrap/Modal';
import Form from 'react-bootstrap/Form';

class ModalDeliver extends Component {
  constructor(props) {
    super(props);

    this.state = {
      error: null,
      isLoading: false,
      showForm: false,
      showResult: false,
      deliveryServices: [],
      deliveryServiceId: '1',
      trackingCode: '',
      formValidated: false,
    };

    this.fetchDeliveryServices = this.fetchDeliveryServices.bind(this);
    this.deliverOrder = this.deliverOrder.bind(this);
    this.handleHideModal = this.handleHideModal.bind(this);
    this.handelSubmitForm = this.handelSubmitForm.bind(this);
    this.handleShowForm = this.handleShowForm.bind(this);
    this.handleInputChange = this.handleInputChange.bind(this);
    this.renderTrackingCodeInput = this.renderTrackingCodeInput.bind(this);
    this.renderForm = this.renderForm.bind(this);
  }

  fetchDeliveryServices() {
    const { fetchServicesUrl } = this.props;

    fetch(fetchServicesUrl, {
      headers: new Headers({
        'X-Requested-With': 'XMLHttpRequest',
      }),
    })
      .then(response => response.json())
      .then(
        (result) => {
          this.setState({ deliveryServices: result.data.items });
        },
        (error) => {
          this.setState({ error });
        },
      );
  }

  deliverOrder(orderId, deliveryServiceId, trackingCode) {
    const { url } = this.props;
    const formData = new FormData();

    formData.append('id', orderId);
    formData.append('delivery_service_id', deliveryServiceId);
    formData.append('tracking_code', trackingCode);

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

  handleHideModal() {
    const { showForm, showResult } = this.state;

    this.props.onHide();

    if (showForm) {
      this.setState({
        deliveryServiceId: '1',
        trackingCode: '',
        showForm: false,
      });
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

  handleShowForm(event) {
    event.target.blur();

    this.setState({ showForm: true }, () => {
      this.fetchDeliveryServices();
    });
  }

  handelSubmitForm(event) {
    event.preventDefault();
    event.stopPropagation();
    event.target.blur();

    const form = event.currentTarget;

    if (form.checkValidity()) {
      const { orderId } = this.props;
      const { deliveryServiceId, trackingCode } = this.state;

      this.setState({
        isLoading: true,
      }, () => {
        this.deliverOrder(orderId, deliveryServiceId, trackingCode);
      });
    }

    this.setState({ formValidated: true });
  }

  handleInputChange(event) {
    this.setState({
      [event.target.name]: event.target.value,
    });
  }

  renderTrackingCodeInput() {
    const { deliveryServiceId } = this.state;

    if (!deliveryServiceId || deliveryServiceId === '1') {
      return (
        <Form.Group controlId="deliverOrderFormTrackingCode">
          <Form.Label>Трек-номер заказа</Form.Label>
          <Form.Control
            name="trackingCode"
            type="text"
            onChange={this.handleInputChange}/>
        </Form.Group>
      );
    }

    return (
      <Form.Group controlId="deliverOrderFormTrackingCode">
        <Form.Label>Трек-номер заказа</Form.Label>
        <Form.Control
          name="trackingCode"
          type="text"
          required
          onChange={this.handleInputChange}/>
        <Form.Control.Feedback type="invalid">Обязательное поле.</Form.Control.Feedback>
      </Form.Group>
    );
  }

  renderForm() {
    const { deliveryServices, deliveryServiceId, formValidated } = this.state;

    return (
      <Fragment>
        <Form
          id="deliverOrderForm"
          noValidate={true}
          validated={formValidated}
          onSubmit={this.handelSubmitForm}>
          <Form.Group controlId="deliverOrderFormDeliveryServiceId">
            <Form.Label>Логистическая компания</Form.Label>
            <Form.Control
              as="select"
              name="deliveryServiceId"
              value={deliveryServiceId}
              required
              onChange={this.handleInputChange}>
              {deliveryServices.map(item => (
                <option key={item.id} value={item.id}>{item.name}</option>
              ))}
            </Form.Control>
            <Form.Control.Feedback type="invalid">Обязательное поле.</Form.Control.Feedback>
          </Form.Group>
          {this.renderTrackingCodeInput()}
        </Form>
      </Fragment>
    );
  }

  renderModalInner() {
    const {
      error,
      isLoading,
      deliveryServiceId,
      showForm,
      showResult,
    } = this.state;

    if (error) {
      return (
        <Fragment>
          <Modal.Header closeButton>
            <Modal.Title id="deliverModalLabel">Ошибка</Modal.Title>
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
      let resultText = 'Мы сами отследим его путь к покупателю. Теперь заказ отображается во вкладке '
        + '"Автоматический контроль" раздела "Ожидают доставки".';

      if (deliveryServiceId === '1') {
        resultText = 'Заказ передан на доставку. Теперь он отображается во вкладке "Ручной контроль" '
          + 'раздела "Ожидают доставки". Вам нужно отслеживать его путь к покупателю.';
      }

      return (
        <Fragment>
          <Modal.Header closeButton>
            <Modal.Title id="deliverModalLabel">Заказ передан на доставку</Modal.Title>
          </Modal.Header>
          <Modal.Body>
            <p>{resultText}</p>
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

    if (showForm) {
      return (
        <Fragment>
          <Modal.Header closeButton>
            <Modal.Title id="deliverModalLabel">Введите данные доставки</Modal.Title>
          </Modal.Header>
          <Modal.Body>
            {this.renderForm()}
          </Modal.Body>
          <Modal.Footer>
            <button type="submit" className="btn btn-primary" form="deliverOrderForm">
              Сохранить {isLoading && <i className="fas fa-sync fa-spin" aria-hidden={true}/>}
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
          <Modal.Title id="deliverModalLabel">Передать заказ на доставку?</Modal.Title>
        </Modal.Header>
        <Modal.Footer>
          <button
            type="button"
            className="btn btn-primary"
            onClick={this.handleShowForm}>
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
        aria-labelledby="deliverModalLabel"
        centered>
        {this.renderModalInner()}
      </Modal>
    );
  }
}

export default ModalDeliver;
