import React, { Component, Fragment } from 'react';
import Modal from 'react-bootstrap/Modal';
import Form from 'react-bootstrap/Form';
import Col from 'react-bootstrap/Col';
import Row from 'react-bootstrap/Row';
import Inputmask from 'inputmask';

class ModalCreate extends Component {
  constructor(props) {
    super(props);

    this.state = {
      error: null,
      isLoading: false,
      showResult: false,
      itemsAmount: 1,
      itemsPrices: [0],
      orderPrice: 0,
      formValidated: false,
      resultText: '',
    };

    this.createOrder = this.createOrder.bind(this);
    this.handleAddItem = this.handleAddItem.bind(this);
    this.handleRemoveItem = this.handleRemoveItem.bind(this);
    this.handleCreateOrder = this.handleCreateOrder.bind(this);
    this.handleHideModal = this.handleHideModal.bind(this);
    this.handelSubmitForm = this.handelSubmitForm.bind(this);
    this.changeOrderPrice = this.changeOrderPrice.bind(this);
    this.renderItemControls = this.renderItemControls.bind(this);
    this.renderForm = this.renderForm.bind(this);
    this.renderModalInner = this.renderModalInner.bind(this);
  }

  componentDidUpdate() {
    if (this.refs.phone) {
      Inputmask('+7(999)999-99-99').mask(this.refs.phone);
    }
  }

  componentWillUnmount() {
    if (this.refs.phone) {
      Inputmask.remove(this.refs.phone);
    }
  }

  createOrder(formData) {
    const { url } = this.props;

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
          } else {
            this.setState({
              resultText: 'Покупателю отправлено sms с ссылкой на оформление кредита. Заказ перемещён во'
                + ' вкладку "Созданные заказы".',
            });
          }
        },
        (error) => {
          this.setState({ error, isLoading: false, showResult: true });
        },
      );
  }

  handleAddItem(event) {
    event.preventDefault();

    event.target.blur();

    const { itemsAmount, itemsPrices } = this.state;
    const newItemPrices = itemsPrices.slice();

    newItemPrices.push(0);

    this.setState({
      itemsAmount: itemsAmount + 1,
      itemsPrices: newItemPrices,
    });
  }

  handleRemoveItem(event) {
    event.preventDefault();

    event.target.blur();

    const { itemsAmount, itemsPrices } = this.state;
    const newItemPrices = itemsPrices.slice();

    newItemPrices.pop();

    this.setState({
      itemsAmount: itemsAmount - 1,
      itemsPrices: newItemPrices,
    }, () => {
      this.changeOrderPrice();
    });
  }

  handleCreateOrder(event) {
    event.target.blur();

    const { orderId } = this.props;

    this.createOrder(orderId);
  }

  handleHideModal() {
    const { showResult } = this.state;

    this.setState({
      itemsAmount: 1,
      itemsPrices: [0],
      orderPrice: 0,
      formValidated: false,
    }, () => {
      this.props.onHide();
    });

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

  handelSubmitForm(event) {
    event.preventDefault();
    event.stopPropagation();

    const form = event.currentTarget;

    if (form.checkValidity()) {
      const { orderPrice } = this.state;
      const formData = new FormData(form);

      formData.append('order_price', orderPrice);

      for (let i = 0; i < this.state.itemsAmount; i += 1) {
        const value = formData.has(`goods[${i}][is_returnable]`) ? '1' : '0';
        formData.set(`goods[${i}][is_returnable]`, value);
      }

      this.setState({
        isLoading: true,
      }, () => {
        this.createOrder(formData);
      });
    }

    this.setState({ formValidated: true });
  }

  changeOrderPrice() {
    let orderPrice = 0;

    for (let i = 0; i < this.state.itemsAmount; i += 1) {
      const price = this.refs[`price_${i}`].value;
      const quantity = this.refs[`quantity_${i}`].value;
      const { itemsPrices } = this.state;

      if (price > 0 && quantity > 0) {
        itemsPrices[i] = price * quantity;
        orderPrice += price * quantity;
      }
    }

    if (orderPrice !== this.state.orderPrice) {
      this.setState({ orderPrice });
    }
  }

  renderItemControls(i) {
    const { itemsPrices } = this.state;

    return (
      <Form.Row key={i} className="mb-1">
        <Col md={3}>
          <Form.Group controlId={`createOrderFormName-${i}`}>
            <div className="d-flex align-items-baseline">
              <Form.Label className="text-nowrap mr-2">Товар</Form.Label>
              <Form.Control
                name={`goods[${i}][name]`}
                type="text"
                required/>
            </div>
          </Form.Group>
        </Col>

        <Col className="col-auto">
          <Form.Group controlId={`createOrderFormIsReturnable-${i}`}>
            <div className="d-flex pt-2">
              <Form.Label className="text-nowrap mr-2">Возвратный</Form.Label>
              <Form.Check
                type="checkbox"
                name={`goods[${i}][is_returnable]`}/>
            </div>
          </Form.Group>
        </Col>

        <Col>
          <Form.Group controlId={`createOrderFormPrice-${i}`}>
            <div className="d-flex align-items-baseline">
              <Form.Label className="text-nowrap mr-2">Цена</Form.Label>
              <Form.Control
                name={`goods[${i}][price]`}
                type="number"
                min="0"
                step="any"
                ref={`price_${i}`}
                required
                onChange={this.changeOrderPrice}/>
            </div>
          </Form.Group>
        </Col>

        <Col>
          <Form.Group controlId={`createOrderFormQuantity-${i}`}>
            <div className="d-flex align-items-baseline">
              <Form.Label className="text-nowrap mr-2">Кол-во</Form.Label>
              <Form.Control
                name={`goods[${i}][quantity]`}
                type="number"
                min="1"
                ref={`quantity_${i}`}
                required
                onChange={this.changeOrderPrice}/>
            </div>
          </Form.Group>
        </Col>

        <Col>
          <Form.Group controlId={`createOrderFormItemsPrices-${i}`}>
            <div className="d-flex align-items-baseline">
              <Form.Label className="text-nowrap mr-2">Сумма</Form.Label>
              <Form.Control
                type="number"
                value={itemsPrices[i]}
                className="create-order-form__form-control"
                readOnly={true}/>
            </div>
          </Form.Group>
        </Col>


      </Form.Row>
    );
  }

  renderForm() {
    const {
      itemsAmount,
      orderPrice,
      formValidated,
    } = this.state;

    return (
      <Fragment>
        <Form
          id="createOrderForm"
          noValidate={true}
          validated={formValidated}
          onSubmit={this.handelSubmitForm}>

          <h4 className="mb-4">Номер телефона покупателя</h4>

          <Row className="mb-4">
            <Col md={3}>
              <Form.Group controlId="createOrderFormCustomerPhone">
                <Form.Control
                  name="phone"
                  type="text"
                  ref="phone"
                  placeholder="+7(___)___-__-__"
                  required/>
              </Form.Group>
            </Col>
          </Row>

          <div className="d-flex align-items-baseline mb-4">
            <h4 className="mr-4">Данные о заказе</h4>

            <button
              type="button"
              className="btn btn-primary btn-sm"
              onClick={this.handleAddItem}>
              Добавить товар
            </button>
          </div>

          <div className="mb-4">
            {[...Array(itemsAmount)].map((item, index) => (
              this.renderItemControls(index)
            ))}
          </div>

          {itemsAmount >= 2 && <button
            type="button"
            className="btn btn-sm btn-secondary ml-1"
            onClick={this.handleRemoveItem}>
            Удалить товар
          </button>}

          <div className="text-right">
            Итого: {orderPrice} руб.
          </div>

        </Form>
      </Fragment>
    );
  }

  renderModalInner() {
    const {
      error,
      isLoading,
      showResult,
      resultText,
    } = this.state;

    if (error) {
      return (
        <Fragment>
          <Modal.Header closeButton>
            <Modal.Title id="createModalLabel">Ошибка</Modal.Title>
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
            <Modal.Title id="createModalLabel">Заказ успешно сформирован</Modal.Title>
          </Modal.Header>
          <Modal.Body>
            <p dangerouslySetInnerHTML={{ __html: resultText }}/>
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
        </Modal.Header>
        <Modal.Body>
          {this.renderForm()}
        </Modal.Body>
        <Modal.Footer>
          <button
            type="button"
            className="btn btn-secondary"
            onClick={this.handleHideModal}>
            Отмена
          </button>
          <button
            type="submit"
            className="btn btn-primary"
            form="createOrderForm">
            Отправить анкету покупателю {isLoading && <i className="fas fa-sync fa-spin" aria-hidden={true}/>}
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
        aria-labelledby="createModalLabel"
        size="xl"
        centered>
        {this.renderModalInner()}
      </Modal>
    );
  }
}

export default ModalCreate;
