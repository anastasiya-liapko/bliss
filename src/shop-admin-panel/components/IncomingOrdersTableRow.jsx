import React, { Component } from 'react';
import Helper from './Helper.jsx';

class IncomingOrdersTableRow extends Component {
  constructor(props) {
    super(props);

    this.handleTableRowClick = this.handleTableRowClick.bind(this);
    this.handleQuickEditButtonClick = this.handleQuickEditButtonClick.bind(this);
  }

  handleTableRowClick() {
    /**
     * @param item.id
     * @param item.order_id_in_shop
     * @param item.order_price
     * @param item.goods
     * @param item.status
     * @param item.time_of_creation
     * @param item.tracking_code
     * @param item.delivery_service_name
     * @param item.mfi_name
     */
    const { item } = this.props;

    this.props.onTableRowClick(item.id);
  }

  handleQuickEditButtonClick(event) {
    event.preventDefault();
    event.stopPropagation();

    /**
     * @param item.id
     * @param item.order_id_in_shop
     * @param item.order_price
     * @param item.goods
     * @param item.status
     * @param item.time_of_creation
     * @param item.tracking_code
     * @param item.delivery_service_name
     * @param item.mfi_name
     */
    const { item } = this.props;

    this.props.onQuickEditButtonClick(item.id);
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
     * @param item.delivery_service_name
     * @param item.mfi_name
     */
    const { item, hasEditButton } = this.props;

    return (
      <tr onClick={this.handleTableRowClick} className="table_tr">
        <td>{item.order_id_in_shop}</td>
        <td>{Helper.localizeDate(item.time_of_creation)}</td>
        <td>{Helper.normalizeGoods(item.goods)}</td>
        <td>{Helper.localizeNumber(item.order_price)} руб.</td>
        <td>{Helper.getOrderStatusName(item.status)}</td>
        {hasEditButton && <td onClick={this.handleQuickEditButtonClick}>
          <button
            type="button"
            className="btn btn-secondary"
            title="Быстрое редактирование"
            onClick={this.handleQuickEditButtonClick}>
            <i className="fas fa-edit" aria-hidden="true"/>
          </button>
        </td>}
      </tr>
    );
  }
}

export default IncomingOrdersTableRow;
