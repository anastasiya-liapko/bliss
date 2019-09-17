import React, { Component, Fragment } from 'react';
import Modal from 'react-bootstrap/Modal';
import Helper from './Helper.jsx';
import TableFilterInfo from './TableFilterInfo.jsx';
import TableHeader from './TableHeader.jsx';
import TablePagination from './TablePagination.jsx';
import TableStatistics from './TableStatistics.jsx';
import DeliveringOrdersTableRow from './DeliveringOrdersTableRow.jsx';
import ModalDecline from './ModalDecline.jsx';
import ModalIssue from './ModalIssue.jsx';

class DeliveringOrdersTable extends Component {
  constructor(props) {
    super(props);

    this.state = {
      quickEditOrderId: null,
      showQuickEditModal: false,
      showDeclineModal: false,
      showIssueModal: false,
    };

    this.handleFilterCloseClick = this.handleFilterCloseClick.bind(this);
    this.handleTableSortClick = this.handleTableSortClick.bind(this);
    this.handleTableFilterClick = this.handleTableFilterClick.bind(this);
    this.handleTableRowClick = this.handleTableRowClick.bind(this);
    this.handlePageClick = this.handlePageClick.bind(this);
    this.handleReloadItems = this.handleReloadItems.bind(this);
    this.handleQuickEditButtonClick = this.handleQuickEditButtonClick.bind(this);
    this.handleShowQuickEditModal = this.handleShowQuickEditModal.bind(this);
    this.handleHideQuickEditModal = this.handleHideQuickEditModal.bind(this);
    this.handleShowDeclineModal = this.handleShowDeclineModal.bind(this);
    this.handleHideDeclineModal = this.handleHideDeclineModal.bind(this);
    this.handleShowIssueModal = this.handleShowIssueModal.bind(this);
    this.handleHideIssueModal = this.handleHideIssueModal.bind(this);
  }

  handleFilterCloseClick() {
    this.props.onFilterCloseClick();
  }

  handleTableSortClick(orderBy) {
    this.props.onTableSortClick(orderBy);
  }

  handleTableFilterClick(orderBy, start, end) {
    this.props.onTableFilterClick(orderBy, start, end);
  }

  handleTableRowClick(orderId) {
    this.props.onTableRowClick(orderId);
  }

  handlePageClick(data) {
    this.props.onPageClick(data);
  }

  handleReloadItems() {
    this.props.onReloadItems();
  }

  handleQuickEditButtonClick(OrderId) {
    this.setState({
      quickEditOrderId: OrderId,
    }, () => {
      this.handleShowQuickEditModal();
    });
  }

  handleShowQuickEditModal() {
    this.setState({ showQuickEditModal: true });
  }

  handleHideQuickEditModal() {
    this.setState({ showQuickEditModal: false });
  }

  handleShowDeclineModal() {
    this.handleHideQuickEditModal();
    this.setState({ showDeclineModal: true });
  }

  handleHideDeclineModal() {
    this.setState({ showDeclineModal: false });
  }

  handleShowIssueModal() {
    this.handleHideQuickEditModal();
    this.setState({ showIssueModal: true });
  }

  handleHideIssueModal() {
    this.setState({ showIssueModal: false });
  }

  render() {
    const {
      hasEditButton,
      error,
      isLoading,
      items,
      orderBy,
      isDescending,
      filterBy,
      filterName,
      filterStart,
      filterEnd,
      totalItems,
      totalSum,
      pageCount,
      offset,
    } = this.props;

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

    const {
      quickEditOrderId,
      showQuickEditModal,
      showDeclineModal,
      showIssueModal,
    } = this.state;

    return (
      <Fragment>
        <TableFilterInfo
          filterName={filterName}
          filterBy={filterBy}
          filterStart={filterStart}
          filterEnd={filterEnd}
          onFilterClose={this.handleFilterCloseClick}/>
        <div className="table-responsive-xl">
          <table className="table table_sticky-header table-striped table-bordered table-hover">
            <thead>
            <tr>
              <TableHeader
                name="№ заказа"
                slug="order_id_in_shop"
                active={orderBy === 'order_id_in_shop'}
                isDescending={isDescending}
                filterInputType="text"
                showSort={true}
                showFilter={true}
                onTableSortClick={this.handleTableSortClick}
                onTableFilterClick={this.handleTableFilterClick}/>
              <TableHeader
                name="Дата и время заказа"
                slug="time_of_creation"
                active={orderBy === 'time_of_creation'}
                isDescending={isDescending}
                filterInputType="date"
                showSort={true}
                showFilter={true}
                onTableSortClick={this.handleTableSortClick}
                onTableFilterClick={this.handleTableFilterClick}/>
              <th className="table__th">Состав заказа</th>
              <th className="table__th">Сумма заказа</th>
              <th className="table__th">Статус заказа</th>
              <th className="table__th">Логистическая компания</th>
              <TableHeader
                name="Трек-номер"
                slug="tracking_code"
                active={orderBy === 'tracking_code'}
                isDescending={isDescending}
                filterInputType="text"
                showSort={true}
                showFilter={true}
                onTableSortClick={this.handleTableSortClick}
                onTableFilterClick={this.handleTableFilterClick}/>
              {hasEditButton && <th className="table__th table__th_sm"/>}
            </tr>
            </thead>
            <tbody>
            {items.map(item => (
              <DeliveringOrdersTableRow
                key={item.id}
                item={item}
                hasEditButton={hasEditButton}
                onTableRowClick={this.handleTableRowClick}
                onQuickEditButtonClick={this.handleQuickEditButtonClick}/>
            ))}
            </tbody>
          </table>
        </div>
        <TableStatistics
          total={totalItems}
          totalSum={totalSum}/>
        <TablePagination
          pageCount={pageCount}
          offset={offset}
          perPage={10}
          onPageClick={this.handlePageClick}/>

        <Modal
          show={showQuickEditModal}
          onHide={this.handleHideQuickEditModal}
          aria-labelledby="quickEditModalLabel"
          centered>
          <Modal.Header closeButton>
            <Modal.Title id="quickEditModalLabel">Выберите действие</Modal.Title>
          </Modal.Header>
          <Modal.Footer>
            <button
              type="button"
              className="btn btn-primary"
              onClick={this.handleShowIssueModal}>
              Подтвердить доставку
            </button>
            <button
              type="button"
              className="btn btn-primary"
              onClick={this.handleShowDeclineModal}>
              Отменить доставку
            </button>
            <button
              type="button"
              className="btn btn-secondary"
              onClick={this.handleHideQuickEditModal}>
              Отмена
            </button>
          </Modal.Footer>
        </Modal>

        <ModalDecline
          show={showDeclineModal}
          url={`${Helper.getLocation()}/shop-admin-panel/delivering-orders/decline`}
          orderId={quickEditOrderId}
          title="Отменить доставку?"
          titleResult="Вы отклонили заказ"
          textResult='Теперь он будет отображаться в разделе "Отмененные заказы". Дальнейших действий по
              данному заказу не требуется.'
          onHide={this.handleHideDeclineModal}
          onFinish={this.handleReloadItems}/>

        <ModalIssue
          show={showIssueModal}
          url={`${Helper.getLocation()}/shop-admin-panel/delivering-orders/issue`}
          orderId={quickEditOrderId}
          title="Подтвердить доставку товара?"
          titleWarning="Внимание!"
          textWarning='Нажимая кнопку "продолжить", вы подтверждаете получение заказа покупателем.'
          titleResult="Вы подтвердили получение заказа покупателем"
          textResult='Теперь он отображается во вкладке "Ожидают поступления оплаты" раздела
            "Оплаченные заказы". В ближайшее время финансовая организация перечислит оплату заказа.'
          onHide={this.handleHideIssueModal}
          onFinish={this.handleReloadItems}/>
      </Fragment>
    );
  }
}

export default DeliveringOrdersTable;
