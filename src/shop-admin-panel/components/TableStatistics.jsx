import React, { Component } from 'react';

class TableStatistics extends Component {
  render() {
    const { totalSum, total } = this.props;
    const totalSumValidated = parseFloat(totalSum) || 0;
    const totalSumLocale = totalSumValidated.toLocaleString('ru-RU');

    return (
      <div className="mt-1 mb-4">
        <p className="lead">
          Всего заказов: <b>{total}</b>
        </p>
        <p className="lead">
          Сумма заказов: <b>{totalSumLocale} руб.</b>
        </p>
      </div>
    );
  }
}

export default TableStatistics;
