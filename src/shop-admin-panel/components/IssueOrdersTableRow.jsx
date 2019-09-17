import React, { Component } from 'react';
import Helper from './Helper.jsx';

class IssueOrdersTableRow extends Component {
  constructor(props) {
    super(props);

    this.handleTableRowClick = this.handleTableRowClick.bind(this);
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
        <td>{item.mfi_name}</td>
      </tr>
    );
  }
}

export default IssueOrdersTableRow;
