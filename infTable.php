  <table id="id2" class="table table-bordered table-striped">
      <thead>
          <tr>
              <th>Patient ID</th>
              <th>Patient Name</th>
              <th>Type</th>
              <th>Status</th>
              <th>Action</th>
              <?php if ($_GET['status'] == 3) { ?>

                  <th>Summary</th>
              <?php } ?>

              <?php
                if ($_GET['status'] == 1) { ?>
                  <th>Screening / Enrollments / Forms</th>
              <?php } ?>
          </tr>
      </thead>
      <tbody>
          <?php
            $x = 1;
            foreach ($clients as $client) {
                $screening = $override->get('screening', 'patient_id', $client['id'])[0];
                $visit = $override->getCount('visit', 'client_id', $client['id']);
                $visits = $override->get('visit', 'client_id', $client['id'])[0];
                $end_study = $override->getNews('screening', 'status', 1, 'patient_id', $client['id'])[0];
                $termination = $override->firstRow1('visit', 'outcome', 'id', 'client_id', $client['id'], 'visit_code', 'TV')[0]['outcome'];
                $type = $override->get('main_diagnosis', 'patient_id', $client['id'])[0];

                $enrollment_date = $override->firstRow2('visit', 'visit_date', 'id', 'client_id', $client['id'], 'seq_no', 1, 'visit_code', 'EV')[0]['visit_date'];

                $screened = 0;
                $eligibility = 0;
                $enrollment = 0;

                if ($client) {
                    if ($client['screened']) {
                        $screened = 1;
                    }
                }

                if ($client) {
                    if ($client['eligible']) {
                        $eligibility = 1;
                    }
                }

                if ($client) {
                    if ($client['enrolled']) {
                        $enrollment = 1;
                    }
                }
            ?>
              <tr>
                  <td><?= $client['study_id'] ?></td>
                  <td><?= $client['firstname'] . ' - ' . $client['lastname'] ?></td>

                  <?php if ($type['cardiac'] == 1) { ?>
                      <td>
                          <a href="#" class="btn btn-default">Cardiac</a>
                      </td>
                  <?php } elseif ($type['diabetes'] == 1) { ?>
                      <td>
                          <a href="#" class="btn btn-info">Diabtes</a>
                      </td>
                  <?php } elseif ($type['sickle_cell'] == 1) { ?>
                      <td>
                          <a href="#" class="btn btn-success">Sickle Cell</a>
                      </td>
                  <?php } else { ?>
                      <td>
                          <a href="#" class="btn btn-warning">Not Diagnosised</a>
                      </td>
                  <?php
                    } ?>

                  <?php if ($_GET['status'] == 1) { ?>

                      <?php if ($client['eligible'] == 1) { ?>
                          <td>
                              <a href="#" class="btn btn-success">Eligible</a>
                          </td>
                      <?php } else { ?>
                          <td>
                              <a href="#" class="btn btn-danger">Not Eligible</a>
                          </td>
                  <?php }
                    } ?>

                  <?php if ($_GET['status'] == 2) { ?>

                      <?php if ($client['enrolled'] == 1) { ?>
                          <td>
                              <a href="#" class="btn btn-success">Enrolled</a>
                          </td>
                      <?php } else { ?>
                          <td>
                              <a href="#" class="btn btn-danger">Not Enrolled</a>
                          </td>
                  <?php }
                    } ?>

                  <?php if ($_GET['status'] == 3) { ?>

                      <?php if ($client['enrolled'] == 1) { ?>
                          <td>
                              <a href="#" class="btn btn-success">Enrolled</a>
                          </td>
                      <?php } else { ?>
                          <td>
                              <a href="#" class="btn btn-danger">Not Enrolled</a>
                          </td>
                  <?php }
                    } ?>



                  <?php if ($_GET['status'] == 4) { ?>
                      <td>
                          <?php if ($client['end_study'] == 1) { ?>
                              <a href="#" class="btn btn-danger">END</a>
                      </td>

                      <td>
                          <?php if ($termination == 1) { ?>
                              <a href="#" class="btn btn-info">On Treatment</a>
                          <?php } elseif ($termination == '2') { ?>

                              <a href="#" class="btn btn-info">Default</a>
                          <?php
                                } elseif ($termination == 3) { ?>
                              <a href="#" class="btn btn-info">Stop treatment</a>
                          <?php
                                } elseif ($termination == 4) { ?>
                              <a href="#" class="btn btn-info">Trnasfer Out</a>
                          <?php
                                } elseif ($termination == 5) { ?>
                              <a href="#" class="btn btn-info">Death</a>
                          <?php
                                } else { ?>
                              <a href="#" class="btn btn-info">Other</a>
                          <?php
                                } ?>
                      </td>


                  <?php } else { ?>
                      <td>
                          <a href="#" class="btn btn-success">ACTIVE</a>
                      </td>
              <?php }
                        } ?>


              <?php if ($_GET['status'] == 5 || $_GET['status'] == 6 || $_GET['status'] == 7 || $_GET['status'] == 8) { ?>

                  <?php if ($client['screened'] == 1) { ?>
                      <td>
                          <a href="#" class="btn btn-success">SCREENED</a>
                      </td>
                  <?php } else { ?>
                      <td>
                          <a href="#" class="btn btn-danger">NOT SCREENED</a>
                      </td>
              <?php }
                } ?>

              <td>
                  <?php if ($_GET['status'] == 1 || $_GET['status'] == 5 || $_GET['status'] == 6 || $_GET['status'] == 7 || $_GET['status'] == 8) { ?>
                      <a href="add.php?id=4&cid=<?= $client['id'] ?>" role="button" class="btn btn-default">View / Update</a>
                      <?php if ($user->data()->power == 1) { ?>
                          <a href="#delete<?= $client['id'] ?>" role="button" class="btn btn-danger" data-toggle="modal">Delete</a>
                      <?php } ?>
              </td>

              <td>


                  <?php if ($screened) { ?>
                      <a href="#addScreening<?= $client['id'] ?>" role="button" class="btn btn-info" data-toggle="modal">
                          Edit Screening
                      </a>
                  <?php } else {  ?>
                      <a href="#addScreening<?= $client['id'] ?>" role="button" class="btn btn-warning" data-toggle="modal">
                          Add Screening
                      </a>
              <?php }
                    } ?>

              <?php if ($_GET['status'] == 2) { ?>
                  <?php if ($enrollment == 1) { ?>
                      <a href="#addEnrollment<?= $client['id'] ?>" role="button" class="btn btn-info" data-toggle="modal">
                          Edit Enrollment
                      </a>
                  <?php } else {  ?>
                      <a href="#addEnrollment<?= $client['id'] ?>" role="button" class="btn btn-warning" data-toggle="modal">
                          Add Enrollment
                      </a>
                  <?php }
                    ?>
              <?php } ?>
              <?php if ($_GET['status'] == 3) { ?>
                  <?php if ($enrollment == 1) { ?>
                      <a href="info.php?id=4&cid=<?= $client['id'] ?>&status=<?= $_GET['status'] ?>" role="button" class="btn btn-warning">Study Crf</a>

              <?php }
                } ?>
              </td>

              <td>
                  <?php if ($_GET['status'] == 3) { ?>
                      <?php if ($enrollment == 1) { ?>
                          <a href="summary.php?cid=<?= $client['id'] ?>" role="button" class="btn btn-primary">Patient Summary</a>

                  <?php }
                    } ?>
              </td>

              </tr>
              <div class="modal fade" id="addScreening<?= $client['id'] ?>">
                  <div class="modal-dialog">
                      <form method="post">
                          <div class="modal-content">
                              <div class="modal-header">
                                  <h4 class="modal-title">SCREENING FORM</h4>
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                  </button>
                              </div>
                              <?php $screening = $override->get('screening', 'patient_id', $client['id'])[0];
                                ?>
                              <div class="modal-body">
                                  <div class="row">
                                      <div class="col-sm-6">
                                          <div class="row-form clearfix">
                                              <!-- select -->
                                              <div class="form-group">
                                                  <label>Date of Screening</label>
                                                  <input class="form-control" type="date" max="<?= date('Y-m-d'); ?>" type="screening_date" name="screening_date" id="screening_date" style="width: 100%;" value="<?php if ($screening['screening_date']) {
                                                                                                                                                                                                                        print_r($screening['screening_date']);
                                                                                                                                                                                                                    }  ?>" required />
                                              </div>
                                          </div>
                                      </div>
                                      <div class="col-sm-6">
                                          <div class="row-form clearfix">
                                              <div class="form-group">
                                                  <label>Consenting individuals?</label>
                                                  <select class="form-control" name="consent" id="consent" style="width: 100%;" onchange="checkQuestionValue1('consent','conset_date')" required>
                                                      <option value="<?= $screening['consent'] ?>"><?php if ($screening) {
                                                                                                        if ($screening['consent'] == 1) {
                                                                                                            echo 'Yes';
                                                                                                        } elseif ($screening['consent'] == 2) {
                                                                                                            echo 'No';
                                                                                                        }
                                                                                                    } else {
                                                                                                        echo 'Select';
                                                                                                    } ?></option>
                                                      <option value="1">Yes</option>
                                                      <option value="2">No</option>
                                                  </select>
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                                  <div class="row">
                                      <div class="col-sm-4">
                                          <div class="row-form clearfix">
                                              <!-- select -->
                                              <div class="form-group">
                                                  <label>Date of Conset</label>
                                                  <input class="form-control" type="date" max="<?= date('Y-m-d'); ?>" type="conset_date" name="conset_date" id="conset_date" style="width: 100%;" value="<?php if ($screening['conset_date']) {
                                                                                                                                                                                                                print_r($screening['conset_date']);
                                                                                                                                                                                                            }  ?>" required />
                                              </div>
                                          </div>
                                      </div>

                                      <div class="col-sm-4">
                                          <div class="row-form clearfix">
                                              <div class="form-group">
                                                  <label>Permanent resident?</label>
                                                  <select class="form-control" name="residence" style="width: 100%;" required>
                                                      <option value="<?= $screening['residence'] ?>"><?php if ($screening) {
                                                                                                            if ($screening['residence'] == 1) {
                                                                                                                echo 'Yes';
                                                                                                            } elseif ($screening['residence'] == 2) {
                                                                                                                echo 'No';
                                                                                                            }
                                                                                                        } else {
                                                                                                            echo 'Select';
                                                                                                        } ?></option>
                                                      <option value="1">Yes</option>
                                                      <option value="2">No</option>
                                                  </select>
                                              </div>
                                          </div>
                                      </div>

                                      <div class="col-sm-4">
                                          <div class="row-form clearfix">
                                              <div class="form-group">
                                                  <label>Known NCD?</label>
                                                  <select class="form-control" name="ncd" style="width: 100%;" required>
                                                      <option value="<?= $screening['scd'] ?>"><?php if ($screening) {
                                                                                                    if ($screening['ncd'] == 1) {
                                                                                                        echo 'Yes';
                                                                                                    } elseif ($screening['ncd'] == 2) {
                                                                                                        echo 'No';
                                                                                                    }
                                                                                                } else {
                                                                                                    echo 'Select';
                                                                                                } ?></option>
                                                      <option value="1">Yes</option>
                                                      <option value="2">No</option>
                                                  </select>
                                              </div>
                                          </div>
                                      </div>

                                  </div>
                              </div>
                              <div class="modal-footer justify-content-between">
                                  <input type="hidden" name="id" value="<?= $client['id'] ?>">
                                  <input type="hidden" name="screening_id" value="<?= $screening['id'] ?>">
                                  <input type="hidden" name="gender" value="<?= $client['gender'] ?>">
                                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                  <input type="submit" name="add_screening" class="btn btn-primary" value="Save changes">
                              </div>
                          </div>
                          <!-- /.modal-content -->
                      </form>
                  </div>
                  <!-- /.modal-dialog -->
              </div>
              <!-- /.modal -->

              <div class="modal fade" id="addEnrollment<?= $client['id'] ?>">
                  <div class="modal-dialog">
                      <form method="post">
                          <div class="modal-content">
                              <div class="modal-header">
                                  <h4 class="modal-title">Visit</h4>
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                  </button>
                              </div>
                              <?php
                                $visits_date = $override->firstRow1('visit', 'visit_date', 'id', 'client_id', $client['id'], 'visit_code', 'EV')[0];
                                $visits_reason = $override->firstRow1('visit', 'reasons', 'id', 'client_id', $client['id'], 'visit_code', 'EV')[0];
                                ?>
                              <div class="modal-body">
                                  <div class="row">
                                      <div class="col-sm-4">
                                          <div class="row-form clearfix">
                                              <!-- select -->
                                              <div class="form-group">
                                                  <label>Date of Enrollment</label>
                                                  <input class="form-control" type="date" max="<?= date('Y-m-d'); ?>" type="visit_date" name="visit_date" id="visit_date" style="width: 100%;" value="<?php if ($screening['visit_date']) {
                                                                                                                                                                                                            print_r($screening['visit_date']);
                                                                                                                                                                                                        }  ?>" required />
                                              </div>
                                          </div>
                                      </div>

                                      <div class="col-sm-8">
                                          <div class="row-form clearfix">
                                              <div class="form-group">
                                                  <label>Notes / Remarks / Comments</label>
                                                  <textarea class="form-control" name="reasons" rows="3">
                                                                                 <?php
                                                                                    if ($visits_reason['reasons']) {
                                                                                        print_r($visits_reason['reasons']);
                                                                                    } ?>
                                                                                </textarea>
                                              </div>
                                          </div>
                                      </div>

                                      <div class="dr"><span></span></div>
                                  </div>
                              </div>
                              <div class="modal-footer justify-content-between">
                                  <input type="hidden" name="id" value="<?= $client['id'] ?>">
                                  <input type="hidden" name="visit_name" value="Enrollment Visit">
                                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                  <input type="submit" name="add_Enrollment" class="btn btn-primary" value="Save changes">
                              </div>
                          </div>
                          <!-- /.modal-content -->
                      </form>
                  </div>
                  <!-- /.modal-dialog -->
              </div>
              <!-- /.modal -->
          <?php $x++;
            } ?>
      </tbody>
      <tfoot>
          <tr>
              <th>Patient ID</th>
              <th>Patient Name</th>
              <th>Type</th>
              <th>Status</th>
              <th>Action</th>
              <?php if ($_GET['status'] == 3) { ?>

                  <th>Summary</th>
              <?php } ?>

              <?php
                if ($_GET['status'] == 1) { ?>
                  <th>Screening / Enrollments / Forms</th>
              <?php } ?>
          </tr>
      </tfoot>
  </table>