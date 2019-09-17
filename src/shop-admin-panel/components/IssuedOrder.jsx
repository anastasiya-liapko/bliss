import React, { Component, Fragment } from 'react';
import Helper from './Helper.jsx';

class IssuedOrder extends Component {
  constructor(props) {
    super(props);

    this.state = {
      error: null,
      isGoodsTableCollapsed: true,
    };

    this.handleCloseItem = this.handleCloseItem.bind(this);
    this.handleTableCollapse = this.handleTableCollapse.bind(this);
    this.renderCustomerBlock = this.renderCustomerBlock.bind(this);
    this.renderCreditBlock = this.renderCreditBlock.bind(this);
  }

  handleCloseItem() {
    this.props.onClose();
  }

  handleTableCollapse() {
    this.setState({ isGoodsTableCollapsed: !this.state.isGoodsTableCollapsed });
  }

  renderCustomerBlock() {
    const { item } = this.props;

    if (!item.customer_name && !item.customer_phone && !item.customer_additional_phone) {
      return null;
    }

    return (
      <div className="mb-3">
        <h4 className="h5">Данные покупателя</h4>
        <div className="table-responsive">
          <table className="table table-sm table-striped table-bordered">
            <tbody>
            {!!item.customer_name && <tr>
              <td>Имя</td>
              <td>{item.customer_name}</td>
            </tr>}
            {!!item.customer_phone && <tr>
              <td>Номер телефона</td>
              <td>{item.customer_phone}</td>
            </tr>}
            {!!item.customer_additional_phone && <tr>
              <td>Доп. номер телефона</td>
              <td>{item.customer_additional_phone}</td>
            </tr>}
            </tbody>
          </table>
        </div>
      </div>
    );
  }

  renderCreditBlock() {
    const { item } = this.props;

    if (!item.request_id && !item.mfi_name && !item.mfi_contract_id && !item.mfi_customer_id) {
      return null;
    }

    return (
      <div className="mb-3">
        <h4 className="h5">Данные по кредиту</h4>
        <div className="table-responsive">
          <table className="table table-sm table-striped table-bordered">
            <tbody>
            {!!item.request_id && <tr>
              <td>Заявка на кредит</td>
              <td>№ {item.request_id}</td>
            </tr>}
            {!!item.mfi_name && <tr>
              <td>ФО, одобрившая кредит</td>
              <td>{item.mfi_name}</td>
            </tr>}
            {!!item.mfi_contract_id && <tr>
              <td>Номер кредита в ФО</td>
              <td>{item.mfi_contract_id}</td>
            </tr>}
            {!!item.mfi_customer_id && <tr>
              <td>Идентификатор покупателя в ФО</td>
              <td>{item.mfi_customer_id}</td>
            </tr>}
            </tbody>
          </table>
        </div>
      </div>
    );
  }

  render() {
    /**
     * @param item.id
     * @param item.order_id_in_shop
     * @param item.order_price
     * @param item.goods
     * @param item.status
     * @param item.time_of_creation
     * @param item.tracking_code
     * @param item.request_id
     * @param item.loan_id
     * @param item.mfi_customer_id
     * @param item.mfi_contract_id
     * @param item.is_mfi_paid
     * @param item.is_mfi_paid
     * @param item.customer_name
     * @param item.customer_phone
     * @param item.customer_additional_phone
     * @param item.mfi_name
     * @param item.delivery_service_name
     */
    const { error, isLoading, item } = this.props;

    if (error) {
      return <div className="mt-4">Произошла ошибка. Перезагрузите страницу,
        возможно, вы не авторизированы в административной панели.</div>;
    }

    if (isLoading) {
      return (
        <div className="loading-container">
          <div>
            <i className="fas fa-sync fa-spin fa-3x" aria-hidden={true}/>
          </div>
        </div>
      );
    }

    return (
      <Fragment>
        <div className="row mt-3">

          <div className="col-sm-9">

            <div className="bg-white p-3">

              <div className="row mb-4">
                <div className="col-1">
                  <button
                    type="button"
                    title="Закрыть"
                    className="btn btn-sm btn-secondary"
                    onClick={this.handleCloseItem}>
                    <i className="fas fa-times" aria-hidden={true}/>
                  </button>
                </div>
                <div className="col-7">
                  <h2 className="h4">
                    Заказ <span className="badge badge-light">№ {item.order_id_in_shop}</span>&nbsp;
                    от <span className="badge badge-light">{Helper.localizeDate(item.time_of_creation)}</span>&nbsp;
                    на сумму <span className="badge badge-light">{Helper.localizeNumber(item.order_price)} руб.</span>
                  </h2>
                </div>
                <div className="col-4">
                  <div className="text-right">
                    <span className="badge badge-primary badge_status text-wrap text-left">
                      {Helper.getOrderStatusName(item.status)}</span>
                  </div>
                </div>
              </div>

              {!!item.delivery_service_name && <div className="mb-5">
                <div className="table-responsive">
                  <table className="table table-sm table-striped table-bordered">
                    <tbody>
                    {!!item.delivery_service_name && <tr>
                      <td>Логистическая компания</td>
                      <td>{item.delivery_service_name}</td>
                    </tr>}
                    {!!item.tracking_code && <tr>
                      <td>Трек-номер</td>
                      <td>{item.tracking_code}</td>
                    </tr>}
                    </tbody>
                  </table>
                </div>
              </div>}

              <div className="mb-4">
                <h4 className="h5">Состав заказа</h4>
                <div className="table-responsive">
                  <table className="table table-sm table-striped table-bordered">
                    <thead>
                    <tr>
                      <th>Наименование товара</th>
                      <th>Цена товара</th>
                      <th>Кол-во</th>
                    </tr>
                    </thead>
                    <tbody>
                    {item.goods.map((i, index) => (
                      <tr key={i.name}
                          className={(index > 2 && this.state.isGoodsTableCollapsed) ? 'd-none' : ''}>
                        <td>{i.name}</td>
                        <td>{Helper.localizeNumber(i.price)} руб.</td>
                        <td>{i.quantity}</td>
                      </tr>
                    ))}
                    </tbody>
                  </table>
                </div>
                {(item.goods.length > 3) && <div>
                  <button
                    className="btn btn-sm btn-secondary"
                    onClick={this.handleTableCollapse}>
                    {this.state.isGoodsTableCollapsed ? 'Развернуть' : 'Свернуть'}
                  </button>
                </div>}
              </div>

              {this.renderCustomerBlock()}

              {this.renderCreditBlock()}

            </div>

          </div>

        </div>

      </Fragment>
    );
  }
}

export default IssuedOrder;
