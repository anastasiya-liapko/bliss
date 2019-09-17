import React, { Component, Fragment } from 'react';
import Modal from 'react-bootstrap/Modal';
import Helper from './Helper.jsx';
import TableFilterInfo from './TableFilterInfo.jsx';
import TableHeader from './TableHeader.jsx';
import IncomingOrdersTableRow from './IncomingOrdersTableRow.jsx';
import TablePagination from './TablePagination.jsx';
import TableStatistics from './TableStatistics.jsx';
import ModalDecline from './ModalDecline.jsx';
import ModalIssue from './ModalIssue.jsx';
import ModalDeliver from './ModalDeliver.jsx';

class IncomingOrdersTable extends Component {
  constructor(props) {
    super(props);

    this.state = {
      quickEditOrderId: null,
      showQuickEditModal: false,
      showDeclineModal: false,
      showDeliverModal: false,
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
    this.handleShowDeliverModal = this.handleShowDeliverModal.bind(this);
    this.handleHideDeliverModal = this.handleHideDeliverModal.bind(this);
    this.handleShowIssueModal = this.handleShowIssueModal.bind(this);
    this.handleHideIssueModal = this.handleHideIssueModal.bind(this);
    this.renderStatusTh = this.renderStatusTh.bind(this);
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

  handleQuickEditButtonClick(orderId) {
    this.setState({
      quickEditOrderId: orderId,
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

  handleShowDeliverModal() {
    this.handleHideQuickEditModal();
    this.setState({ showDeliverModal: true });
  }

  handleHideDeliverModal() {
    this.setState({ showDeliverModal: false });
  }

  handleShowIssueModal() {
    this.handleHideQuickEditModal();
    this.setState({ showIssueModal: true });
  }

  handleHideIssueModal() {
    this.setState({ showIssueModal: false });
  }

  renderStatusTh() {
    const { orderBy, filterByStatus, isDescending } = this.props;

    if (filterByStatus) {
      return (
        <TableHeader
          name="Статус заказа"
          slug="status"
          active={orderBy === 'status'}
          isDescending={isDescending}
          filterInputType="select"
          values={[
            {
              slug: 'pending_by_mfi',
              name: 'На рассмотрении в ФО',
            },
            {
              slug: 'approved_by_mfi',
              name: 'Ожидает подтверждения покупателя',
            },
            {
              slug: 'mfi_did_not_answer',
              name: 'МФО не успели ответить',
            },
          ]}
          showSort={true}
          showFilter={true}
          onTableSortClick={this.handleTableSortClick}
          onTableFilterClick={this.handleTableFilterClick}/>
      );
    }

    return <th className="table__th">Статус заказа</th>;
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
      showDeliverModal,
      showIssueModal,
    } = this.state;

    return (
      <Fragment>
        <TableFilterInfo
          filterName={filterName}
          filterBy={filterBy}
          filterStart={filterStart}
          filterStartNames={{
            status: {
              pending_by_mfi: 'На рассмотрении в ФО',
              approved_by_mfi: 'Ожидает подтверждения покупателя',
              mfi_did_not_answer: 'МФО не успели ответить',
            },
          }}
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
              {this.renderStatusTh()}
              {hasEditButton && <th className="table__th table__th_sm"/>}
            </tr>
            </thead>
            <tbody>
            {items.map(item => (
              <IncomingOrdersTableRow
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
              onClick={this.handleShowDeclineModal}>
              Отклонить заказ
            </button>
            <button
              type="button"
              className="btn btn-primary"
              onClick={this.handleShowDeliverModal}>
              Передать на доставку
            </button>
            <button
              type="button"
              className="btn btn-primary"
              onClick={this.handleShowIssueModal}>
              Выдать заказ
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
          orderId={quickEditOrderId}
          url={`${Helper.getLocation()}/shop-admin-panel/incoming-orders/decline`}
          title="Отклонить заказ?"
          titleResult="Вы отклонили заказ"
          textResult='Теперь он будет отображаться в разделе "Отмененные заказы". Дальнейших действий по
              данному заказу не требуется.'
          onHide={this.handleHideDeclineModal}
          onFinish={this.handleReloadItems}/>

        <ModalDeliver
          show={showDeliverModal}
          url={`${Helper.getLocation()}/shop-admin-panel/incoming-orders/deliver`}
          fetchServicesUrl={`${Helper.getLocation()}/shop-admin-panel/incoming-orders/get-delivery-services`}
          orderId={quickEditOrderId}
          onHide={this.handleHideDeliverModal}
          onFinish={this.handleReloadItems}/>

        <ModalIssue
          show={showIssueModal}
          url={`${Helper.getLocation()}/shop-admin-panel/incoming-orders/issue`}
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

export default IncomingOrdersTable;
