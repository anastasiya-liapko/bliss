import React, { Component, Fragment } from 'react';
import Form from 'react-bootstrap/Form';
import InputGroup from 'react-bootstrap/InputGroup';
import DatePicker from 'react-datepicker';
import ru from 'date-fns/locale/ru';

import 'react-datepicker/dist/react-datepicker.css';

class TableFilterForm extends Component {
  constructor(props) {
    super(props);

    this.state = {
      start: null,
      end: null,
      formValidated: false,
    };

    this.handleStartChange = this.handleStartChange.bind(this);
    this.handleEndChange = this.handleEndChange.bind(this);
    this.handleFormSubmit = this.handleFormSubmit.bind(this);
    this.renderInputs = this.renderInputs.bind(this);
  }

  handleStartChange(value) {
    this.setState({ start: value });
  }

  handleEndChange(value) {
    this.setState({ end: value });
  }

  handleFormSubmit(event) {
    event.preventDefault();
    event.stopPropagation();

    const form = event.currentTarget;
    let start = '';
    let end = '';

    if (form.elements.start) {
      start = form.elements.start.value;
    }

    if (form.elements.end) {
      end = form.elements.end.value;
    }

    if (form.checkValidity()) {
      this.props.onTableFilterClick(start, end);
    }

    this.setState({ formValidated: true });
  }

  renderInputs() {
    const { type } = this.props;

    if (type === 'number') {
      return (
        <Fragment>
          <Form.Control
            name="start"
            type="number"
            placeholder="от"
            required/>
          <Form.Control
            name="end"
            type="number"
            placeholder="до"
            required/>
        </Fragment>
      );
    }

    if (type === 'select') {
      const { values } = this.props;

      return (
        <Fragment>
          <Form.Control
            as="select"
            name="start"
            required>
            {values.map(item => (
              <option key={item.slug} value={item.slug}>{item.name}</option>
            ))}
          </Form.Control>
        </Fragment>
      );
    }

    if (type === 'date') {
      const { start, end } = this.state;

      return (
        <Fragment>
          <DatePicker
            name="start"
            dateFormat="dd.MM.yyyy"
            autoComplete="off"
            className="form-control"
            placeholderText="от"
            selected={start}
            pattern="^(0[1-9]|[12]\d|3[01])\.((0[1-9]|1[0-2])\.[12]\d{3})$"
            locale={ru}
            onChange={this.handleStartChange}
            required/>
          <DatePicker
            name="end"
            dateFormat="dd.MM.yyyy"
            autoComplete="off"
            className="form-control"
            placeholderText="до"
            selected={end}
            pattern="^(0[1-9]|[12]\d|3[01])\.((0[1-9]|1[0-2])\.[12]\d{3})$"
            locale={ru}
            onChange={this.handleEndChange}
            required/>
        </Fragment>
      );
    }

    return (
      <Fragment>
        <Form.Control
          name="start"
          type="text"
          required/>
      </Fragment>
    );
  }

  render() {
    const { formValidated } = this.state;

    return (
      <Form
        noValidate={true}
        validated={formValidated}
        onSubmit={this.handleFormSubmit}>
        <InputGroup className="flex-nowrap">
          {this.renderInputs()}
          <button type="submit" className="btn btn-primary">
            <i className="fas fa-filter"/>
          </button>
        </InputGroup>
      </Form>
    );
  }
}

export default TableFilterForm;
